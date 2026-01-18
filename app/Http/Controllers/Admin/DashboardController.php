<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Room;
use App\Models\Booking;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalOwners = User::where('role', 'owner')->count();
        $totalRenters = User::where('role', 'renter')->count();
        $totalRooms = Room::count();
        $totalBookings = Booking::count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        $approvedBookings = Booking::where('status', 'approved')->count();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalOwners',
            'totalRenters',
            'totalRooms',
            'totalBookings',
            'pendingBookings',
            'approvedBookings'
        ));
    }
}
