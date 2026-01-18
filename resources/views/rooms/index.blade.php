<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Browse Rooms') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-6">
                <form method="GET" action="{{ route('rooms.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">City</label>
                        <input type="text" name="city" id="city" value="{{ request('city') }}" 
                            placeholder="Search by city" 
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                    </div>
                    
                    <div>
                        <label for="room_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Room Type</label>
                        <select name="room_type" id="room_type" 
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            <option value="">All Types</option>
                            <option value="single" {{ request('room_type') == 'single' ? 'selected' : '' }}>Single</option>
                            <option value="double" {{ request('room_type') == 'double' ? 'selected' : '' }}>Double</option>
                            <option value="flat" {{ request('room_type') == 'flat' ? 'selected' : '' }}>Flat</option>
                            <option value="apartment" {{ request('room_type') == 'apartment' ? 'selected' : '' }}>Apartment</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="min_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Min Price</label>
                        <input type="number" name="min_price" id="min_price" value="{{ request('min_price') }}" 
                            placeholder="Min NPR" 
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                    </div>
                    
                    <div>
                        <label for="max_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Max Price</label>
                        <input type="number" name="max_price" id="max_price" value="{{ request('max_price') }}" 
                            placeholder="Max NPR" 
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                    </div>
                    
                    <div class="md:col-span-4 flex gap-2">
                        <button type="submit" 
                            class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-md font-medium">
                            Apply Filters
                        </button>
                        <a href="{{ route('rooms.index') }}" 
                            class="bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 px-6 py-2 rounded-md font-medium">
                            Clear
                        </a>
                    </div>
                </form>
            </div>

            <!-- Rooms Grid -->
            @if ($rooms->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                    @foreach ($rooms as $room)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                                    {{ $room->title }}
                                </h3>
                                <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">
                                    {{ Str::limit($room->description, 100) }}
                                </p>
                                <div class="space-y-2 mb-4">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        📍 {{ $room->city }}, {{ $room->province }}
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        🏠 {{ ucfirst($room->room_type) }}
                                    </p>
                                    <p class="text-lg font-semibold text-blue-600">
                                        NPR {{ number_format($room->rent_price) }}/month
                                    </p>
                                </div>
                                <a href="{{ route('rooms.show', $room) }}" 
                                    class="block w-full text-center bg-blue-500 hover:bg-blue-600 text-white font-medium px-4 py-2 rounded">
                                    View Details
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    {{ $rooms->withQueryString()->links() }}
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-12 text-center">
                    <p class="text-gray-600 dark:text-gray-400 text-lg">No rooms found matching your criteria.</p>
                    <a href="{{ route('rooms.index') }}" class="inline-block mt-4 text-blue-600 hover:text-blue-800 dark:text-blue-400">
                        Clear filters and view all rooms
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
