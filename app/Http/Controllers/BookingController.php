<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'message' => 'nullable|string|max:1000',
        ]);

        $room = Room::findOrFail($request->room_id);

        if ($room->status !== 'available') {
            return back()->with('error', 'This room is not available for booking.');
        }

        Booking::create([
            'room_id' => $request->room_id,
            'renter_id' => auth()->id(),
            'message' => $request->message,
            'status' => 'pending',
            'requested_at' => now(),
        ]);

        return redirect()->route('my-bookings')->with('success', 'Booking request submitted successfully!');
    }

    public function myBookings()
    {
        $bookings = Booking::where('renter_id', auth()->id())
            ->with(['room.owner'])
            ->latest('requested_at')
            ->paginate(10);

        return view('bookings.my-bookings', compact('bookings'));
    }
}
