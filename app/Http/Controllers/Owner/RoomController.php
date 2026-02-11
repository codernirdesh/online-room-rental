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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'available_from' => 'required|date',
            'status' => 'required|in:available,booked,inactive',
        ]);

        $validated['owner_id'] = auth()->id();

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('rooms', 'public');
            $validated['image'] = $imagePath;
        }

        Room::create($validated);

        return redirect()->route('owner.rooms.index')
            ->with('success', 'Room created successfully!');
    }

    public function show(Room $room)
    {
        if ($room->owner_id !== auth()->id()) {
            abort(403);
        }

        $room->load('owner');
        $bookings = $room->bookings()
            ->with('renter')
            ->latest('requested_at')
            ->get();

        return view('owner.rooms.show', compact('room', 'bookings'));
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'available_from' => 'required|date',
            'status' => 'required|in:available,booked,inactive',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($room->image && \Storage::disk('public')->exists($room->image)) {
                \Storage::disk('public')->delete($room->image);
            }
            $imagePath = $request->file('image')->store('rooms', 'public');
            $validated['image'] = $imagePath;
        }

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
