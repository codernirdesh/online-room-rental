<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $latestRooms = Room::where('status', '!=', 'inactive')
            ->with('owner')
            ->latest()
            ->take(6)
            ->get();

        return view('home', compact('latestRooms'));
    }
}
