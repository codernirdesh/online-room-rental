<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
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
