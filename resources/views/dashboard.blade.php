<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <x-success-alert />

            <!-- Welcome -->
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-primary-100 dark:bg-primary-900/30 rounded-full flex items-center justify-center">
                    <span class="text-primary-600 dark:text-primary-400 font-bold text-xl">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Welcome back, {{ auth()->user()->name }}!</h3>
                    <p class="text-gray-500 dark:text-gray-400">Here's what's happening with your {{ auth()->user()->role === 'owner' ? 'properties' : 'bookings' }}.</p>
                </div>
            </div>

            <!-- Stats Cards -->
            @if(auth()->user()->role === 'owner')
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Rooms</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['total_rooms'] }}</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Available</p>
                        <p class="text-3xl font-bold text-green-600 dark:text-green-400 mt-1">{{ $stats['available_rooms'] }}</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Booked</p>
                        <p class="text-3xl font-bold text-blue-600 dark:text-blue-400 mt-1">{{ $stats['booked_rooms'] }}</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 {{ $stats['pending_bookings'] > 0 ? 'ring-2 ring-yellow-200 dark:ring-yellow-800' : '' }}">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pending Approval</p>
                        <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400 mt-1">{{ $stats['pending_bookings'] }}</p>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Bookings</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['total_bookings'] }}</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Bookings</p>
                        <p class="text-3xl font-bold text-green-600 dark:text-green-400 mt-1">{{ $stats['active_bookings'] }}</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Awaiting Confirmation</p>
                        <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400 mt-1">{{ $stats['pending_bookings'] }}</p>
                    </div>
                </div>
            @endif

            <!-- Recent Bookings -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-xl">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ auth()->user()->role === 'owner' ? 'Recent Booking Requests' : 'My Recent Bookings' }}
                        </h3>
                        <a href="{{ route('my-bookings') }}" class="text-sm text-primary-600 hover:text-primary-800 dark:text-primary-400 font-medium">
                            View All &rarr;
                        </a>
                    </div>

                    @if ($bookings->count() > 0)
                        <div class="space-y-4">
                            @foreach ($bookings as $booking)
                                <div class="flex items-center justify-between p-4 rounded-lg bg-gray-50 dark:bg-gray-900 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                                    <div class="flex items-center gap-4 flex-1 min-w-0">
                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-semibold text-gray-900 dark:text-white truncate">{{ $booking->room->title }}</h4>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $booking->room->city }}
                                                @if(auth()->user()->role === 'owner' && $booking->renter)
                                                    &middot; by {{ $booking->renter->name }}
                                                @endif
                                            </p>
                                        </div>
                                        <div class="text-right flex-shrink-0">
                                            <p class="text-sm font-bold text-gray-900 dark:text-white">NPR {{ number_format($booking->room->rent_price) }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($booking->requested_at)->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    <div class="ml-4 flex-shrink-0">
                                        <x-booking-status-badge :status="$booking->status" />
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-10">
                            <svg class="mx-auto h-12 w-12 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <p class="mt-3 text-gray-500 dark:text-gray-400">
                                {{ auth()->user()->role === 'owner' ? 'No booking requests yet.' : "You haven't made any bookings yet." }}
                            </p>
                            <a href="{{ route('rooms.index') }}" class="inline-flex items-center mt-4 px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition">
                                Browse Rooms
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @if(auth()->user()->role === 'owner')
                    <a href="{{ route('owner.rooms.create') }}" class="flex items-center gap-4 p-5 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition group">
                        <div class="w-10 h-10 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center group-hover:bg-primary-200 dark:group-hover:bg-primary-900/50 transition">
                            <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white">List New Room</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Add a new property listing</p>
                        </div>
                    </a>
                    <a href="{{ route('owner.rooms.index') }}" class="flex items-center gap-4 p-5 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition group">
                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center group-hover:bg-blue-200 dark:group-hover:bg-blue-900/50 transition">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white">Manage Rooms</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">View and edit your listings</p>
                        </div>
                    </a>
                    <a href="{{ route('owner.bookings.index') }}" class="flex items-center gap-4 p-5 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition group">
                        <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center group-hover:bg-green-200 dark:group-hover:bg-green-900/50 transition">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white">Booking Requests</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Review and manage bookings</p>
                        </div>
                    </a>
                @else
                    <a href="{{ route('rooms.index') }}" class="flex items-center gap-4 p-5 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition group">
                        <div class="w-10 h-10 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center group-hover:bg-primary-200 dark:group-hover:bg-primary-900/50 transition">
                            <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white">Browse Rooms</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Find your perfect room</p>
                        </div>
                    </a>
                    <a href="{{ route('my-bookings') }}" class="flex items-center gap-4 p-5 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition group">
                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center group-hover:bg-blue-200 dark:group-hover:bg-blue-900/50 transition">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white">My Bookings</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Track all your bookings</p>
                        </div>
                    </a>
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-4 p-5 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition group">
                        <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center group-hover:bg-green-200 dark:group-hover:bg-green-900/50 transition">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white">My Profile</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Update your information</p>
                        </div>
                    </a>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
