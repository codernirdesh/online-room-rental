<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['room', 'renter']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->latest('requested_at')->paginate(20);

        return view('admin.bookings.index', compact('bookings'));
    }

    public function approve(Booking $booking)
    {
        if ($booking->status !== 'paid') {
            return back()->with('error', 'Only bookings with payment can be approved.');
        }

        $booking->update(['status' => 'approved']);
        $booking->room->update(['status' => 'booked']);

        return back()->with('success', 'Booking approved! Payment verified successfully.');
    }

    public function reject(Booking $booking)
    {
        $booking->update(['status' => 'rejected']);

        // If no other active booking exists, make room available again
        if (!$booking->room->hasActiveBooking()) {
            $booking->room->update(['status' => 'available']);
        }

        return back()->with('success', 'Booking rejected.');
    }

    public function show(Booking $booking)
    {
        $booking->load(['room.owner', 'renter']);

        return view('admin.bookings.show', compact('booking'));
    }
}
