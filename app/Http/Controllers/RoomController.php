<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource with filters.
     */
    public function index(Request $request)
    {
        $query = Room::where('status', '!=', 'inactive')
            ->with('owner');

        // Filter by city
        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        // Filter by room type
        if ($request->filled('room_type')) {
            $query->where('room_type', $request->room_type);
        }

        // Filter by minimum price
        if ($request->filled('min_price')) {
            $query->where('rent_price', '>=', $request->min_price);
        }

        // Filter by maximum price
        if ($request->filled('max_price')) {
            $query->where('rent_price', '<=', $request->max_price);
        }

        $rooms = $query->paginate(12);

        return view('rooms.index', compact('rooms'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Room $room)
    {
        $room->load('owner');

        $userBooking = null;
        if (auth()->check() && auth()->user()->role === 'renter') {
            $userBooking = $room->bookings()
                ->where('renter_id', auth()->id())
                ->whereIn('status', ['paid', 'approved'])
                ->first();
        }

        return view('rooms.show', compact('room', 'userBooking'));
    }
}
