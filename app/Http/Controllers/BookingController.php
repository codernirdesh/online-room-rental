<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Models\Setting;
use App\Services\EsewaPaymentService;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Show the checkout page with QR code for payment.
     */
    public function checkout(Room $room)
    {
        if (!$room->isBookable()) {
            return redirect()->route('rooms.show', $room)
                ->with('error', 'This room is not available for booking.');
        }

        $paymentQr = Setting::get('payment_qr');
        $esewaEnabled = EsewaPaymentService::isEnabled();

        return view('bookings.checkout', compact('room', 'paymentQr', 'esewaEnabled'));
    }

    /**
     * Process the booking with payment screenshot upload.
     */
    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'message' => 'nullable|string|max:1000',
            'payment_screenshot' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $room = Room::findOrFail($request->room_id);

        if (!$room->isBookable()) {
            return back()->with('error', 'This room is no longer available for booking.');
        }

        // Store payment screenshot
        $screenshotPath = $request->file('payment_screenshot')->store('payments', 'public');

        // Create booking with paid status
        Booking::create([
            'room_id' => $request->room_id,
            'renter_id' => auth()->id(),
            'message' => $request->message,
            'status' => 'paid',
            'payment_method' => 'qr',
            'payment_screenshot' => $screenshotPath,
            'paid_at' => now(),
            'requested_at' => now(),
        ]);

        // Mark room as booked since payment is done
        $room->update(['status' => 'booked']);

        return redirect()->route('my-bookings')->with('success', 'Payment submitted successfully! Your booking is awaiting confirmation from the owner.');
    }

    /**
     * Show bookings - renter sees their bookings, owner sees bookings on their rooms.
     */
    public function myBookings()
    {
        $user = auth()->user();

        if ($user->role === 'owner') {
            $bookings = Booking::whereHas('room', function ($query) use ($user) {
                $query->where('owner_id', $user->id);
            })
            ->with(['room', 'renter'])
            ->latest('requested_at')
            ->paginate(10);
        } else {
            $bookings = Booking::where('renter_id', $user->id)
                ->with(['room.owner'])
                ->latest('requested_at')
                ->paginate(10);
        }

        return view('bookings.my-bookings', compact('bookings'));
    }
}
