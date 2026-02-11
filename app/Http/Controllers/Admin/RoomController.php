<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::with('owner')
            ->withCount('bookings')
            ->latest()
            ->paginate(15);

        return view('admin.rooms.index', compact('rooms'));
    }

    public function create()
    {
        $owners = User::where('role', 'owner')->orderBy('name')->get();

        return view('admin.rooms.create', compact('owners'));
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
            'owner_id' => 'required|exists:users,id',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('rooms', 'public');
            $validated['image'] = $imagePath;
        }

        Room::create($validated);

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Room created successfully!');
    }

    public function show(Room $room)
    {
        $room->load('owner');
        $bookings = $room->bookings()
            ->with('renter')
            ->latest('requested_at')
            ->get();

        return view('admin.rooms.show', compact('room', 'bookings'));
    }

    public function edit(Room $room)
    {
        $owners = User::where('role', 'owner')->orderBy('name')->get();

        return view('admin.rooms.edit', compact('room', 'owners'));
    }

    public function update(Request $request, Room $room)
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
            'owner_id' => 'required|exists:users,id',
        ]);

        if ($request->hasFile('image')) {
            if ($room->image && \Storage::disk('public')->exists($room->image)) {
                \Storage::disk('public')->delete($room->image);
            }
            $imagePath = $request->file('image')->store('rooms', 'public');
            $validated['image'] = $imagePath;
        }

        $room->update($validated);

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Room updated successfully!');
    }

    public function destroy(Room $room)
    {
        if ($room->image && \Storage::disk('public')->exists($room->image)) {
            \Storage::disk('public')->delete($room->image);
        }

        $room->delete();

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Room deleted successfully!');
    }

    public function deactivate(Room $room)
    {
        $room->update(['status' => 'inactive']);

        return back()->with('success', 'Room deactivated successfully!');
    }

    public function activate(Room $room)
    {
        $room->update(['status' => 'available']);

        return back()->with('success', 'Room activated successfully!');
    }
}
