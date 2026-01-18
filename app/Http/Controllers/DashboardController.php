<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'owner') {
            return redirect()->route('owner.rooms.index');
        }

        // Renter dashboard
        $bookings = Booking::where('renter_id', $user->id)
            ->with('room')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact('bookings'));
    }
}
