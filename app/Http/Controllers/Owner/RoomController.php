<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::where('owner_id', auth()->id())
            ->withCount('bookings')
            ->latest()
            ->paginate(10);

        return view('owner.rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('owner.rooms.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'rent_price' => 'required|numeric|min:0',
            'room_type' => 'required|in:single,double,flat,apartment',
            'amenities' => 'nullable|string',
            'available_from' => 'required|date',
            'status' => 'required|in:available,booked,inactive',
        ]);

        $validated['owner_id'] = auth()->id();

        Room::create($validated);

        return redirect()->route('owner.rooms.index')
            ->with('success', 'Room created successfully!');
    }

    public function edit(Room $room)
    {
        if ($room->owner_id !== auth()->id()) {
            abort(403);
        }

        return view('owner.rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room)
    {
        if ($room->owner_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'rent_price' => 'required|numeric|min:0',
            'room_type' => 'required|in:single,double,flat,apartment',
            'amenities' => 'nullable|string',
            'available_from' => 'required|date',
            'status' => 'required|in:available,booked,inactive',
        ]);

        $room->update($validated);

        return redirect()->route('owner.rooms.index')
            ->with('success', 'Room updated successfully!');
    }

    public function destroy(Room $room)
    {
        if ($room->owner_id !== auth()->id()) {
            abort(403);
        }

        $room->delete();

        return redirect()->route('owner.rooms.index')
            ->with('success', 'Room deleted successfully!');
    }
}
