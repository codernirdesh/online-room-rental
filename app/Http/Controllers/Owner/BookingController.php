<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::whereHas('room', function ($query) {
            $query->where('owner_id', auth()->id());
        })
        ->with(['room', 'renter'])
        ->latest('requested_at')
        ->paginate(15);

        return view('owner.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        if ($booking->room->owner_id !== auth()->id()) {
            abort(403);
        }

        $booking->load(['room', 'renter']);

        return view('owner.bookings.show', compact('booking'));
    }

    public function approve(Booking $booking)
    {
        if ($booking->room->owner_id !== auth()->id()) {
            abort(403);
        }

        if ($booking->status !== 'paid') {
            return back()->with('error', 'Only bookings with payment can be approved.');
        }

        $booking->update(['status' => 'approved']);
        $booking->room->update(['status' => 'booked']);

        return back()->with('success', 'Booking approved! The payment has been verified.');
    }

    public function reject(Booking $booking)
    {
        if ($booking->room->owner_id !== auth()->id()) {
            abort(403);
        }

        $booking->update(['status' => 'rejected']);

        // If no other active booking exists, make room available again
        if (!$booking->room->hasActiveBooking()) {
            $booking->room->update(['status' => 'available']);
        }

        return back()->with('success', 'Booking rejected. The room is available again.');
    }
}
