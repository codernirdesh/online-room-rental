<x-public-layout title="Browse Rooms - RoomRent" activePage="rooms">
    <div class="pt-32 pb-16 px-4">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white mb-4 tracking-tight">
                    Find Your <span class="text-primary-600 underline decoration-primary-300 decoration-4 underline-offset-4">Perfect Room</span>
                </h1>
                <p class="text-lg text-gray-500 dark:text-gray-400">{{ $rooms->total() }} rooms available</p>
            </div>

            <!-- Modern Filters -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 mb-12">
                <form method="GET" action="{{ route('rooms.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label for="city" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">City</label>
                            <input type="text" name="city" id="city" value="{{ request('city') }}" 
                                placeholder="Enter city name" 
                                class="w-full rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:ring-2 focus:ring-primary-500 focus:border-transparent py-2.5">
                        </div>
                        
                        <div>
                            <label for="room_type" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Room Type</label>
                            <select name="room_type" id="room_type" 
                                class="w-full rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:ring-2 focus:ring-primary-500 focus:border-transparent py-2.5">
                                <option value="">All Types</option>
                                <option value="single" {{ request('room_type') == 'single' ? 'selected' : '' }}>Single</option>
                                <option value="double" {{ request('room_type') == 'double' ? 'selected' : '' }}>Double</option>
                                <option value="flat" {{ request('room_type') == 'flat' ? 'selected' : '' }}>Flat</option>
                                <option value="apartment" {{ request('room_type') == 'apartment' ? 'selected' : '' }}>Apartment</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="min_price" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Min Price (NPR)</label>
                            <input type="number" name="min_price" id="min_price" value="{{ request('min_price') }}" 
                                placeholder="0" 
                                class="w-full rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:ring-2 focus:ring-primary-500 focus:border-transparent py-2.5">
                        </div>
                        
                        <div>
                            <label for="max_price" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Max Price (NPR)</label>
                            <input type="number" name="max_price" id="max_price" value="{{ request('max_price') }}" 
                                placeholder="100000" 
                                class="w-full rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:ring-2 focus:ring-primary-500 focus:border-transparent py-2.5">
                        </div>
                    </div>
                    
                    <div class="flex gap-3 pt-2">
                        <button type="submit" 
                            class="flex-1 md:flex-none px-8 py-3 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition font-semibold shadow-sm hover:shadow-md">
                            Search Rooms
                        </button>
                        <a href="{{ route('rooms.index') }}" 
                            class="px-6 py-3 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-xl transition font-semibold">
                            Clear
                        </a>
                    </div>
                </form>
            </div>

            <!-- Rooms Grid -->
            @if ($rooms->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                    @foreach ($rooms as $room)
                        <x-room-card :room="$room" />
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                    {{ $rooms->withQueryString()->links() }}
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-12 text-center">
                    <svg class="w-24 h-24 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-gray-600 dark:text-gray-400 text-lg mb-4">No rooms found matching your criteria.</p>
                    <a href="{{ route('rooms.index') }}" class="inline-flex items-center px-6 py-3 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition font-semibold shadow-sm text-center">
                        View All Rooms
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-public-layout>
