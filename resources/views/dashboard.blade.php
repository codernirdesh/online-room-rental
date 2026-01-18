<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">My Recent Bookings</h3>
                    
                    @if ($bookings->count() > 0)
                        <div class="space-y-4">
                            @foreach ($bookings as $booking)
                                <div class="border-b dark:border-gray-700 pb-4">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="font-medium">{{ $booking->room->title }}</h4>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $booking->room->city }}</p>
                                            <p class="text-sm mt-1">NPR {{ number_format($booking->room->rent_price, 2) }}/month</p>
                                        </div>
                                        <span class="px-3 py-1 text-sm rounded-full 
                                            @if($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($booking->status === 'approved') bg-green-100 text-green-800
                                            @elseif($booking->status === 'rejected') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-6">
                            <a href="{{ route('my-bookings') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                View All Bookings →
                            </a>
                        </div>
                    @else
                        <p class="text-gray-600 dark:text-gray-400">You haven't made any bookings yet.</p>
                        <a href="{{ route('rooms.index') }}" class="inline-block mt-4 text-blue-600 hover:text-blue-800 dark:text-blue-400">
                            Browse Available Rooms →
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
