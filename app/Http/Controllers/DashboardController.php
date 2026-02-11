<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'owner') {
            $rooms = Room::where('owner_id', $user->id)->get();
            $bookings = Booking::whereHas('room', function ($q) use ($user) {
                $q->where('owner_id', $user->id);
            })->with(['room', 'renter'])->latest('requested_at')->take(5)->get();

            $stats = [
                'total_rooms' => $rooms->count(),
                'available_rooms' => $rooms->where('status', 'available')->count(),
                'booked_rooms' => $rooms->where('status', 'booked')->count(),
                'pending_bookings' => Booking::whereHas('room', fn($q) => $q->where('owner_id', $user->id))->where('status', 'paid')->count(),
            ];

            return view('dashboard', compact('bookings', 'stats'));
        }

        // Renter dashboard
        $bookings = Booking::where('renter_id', $user->id)
            ->with('room')
            ->latest()
            ->take(5)
            ->get();

        $stats = [
            'total_bookings' => Booking::where('renter_id', $user->id)->count(),
            'active_bookings' => Booking::where('renter_id', $user->id)->whereIn('status', ['paid', 'approved'])->count(),
            'pending_bookings' => Booking::where('renter_id', $user->id)->where('status', 'paid')->count(),
        ];

        return view('dashboard', compact('bookings', 'stats'));
    }
}
