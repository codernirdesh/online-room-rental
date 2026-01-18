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

    public function approve(Booking $booking)
    {
        if ($booking->room->owner_id !== auth()->id()) {
            abort(403);
        }

        $booking->update(['status' => 'approved']);
        $booking->room->update(['status' => 'booked']);

        return back()->with('success', 'Booking approved successfully!');
    }

    public function reject(Booking $booking)
    {
        if ($booking->room->owner_id !== auth()->id()) {
            abort(403);
        }

        $booking->update(['status' => 'rejected']);

        return back()->with('success', 'Booking rejected!');
    }
}
