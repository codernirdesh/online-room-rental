<x-public-layout title="RoomRent - Find Your Perfect Room" activePage="home">
    <!-- Hero Section -->
    <div class="pt-32 pb-20 px-4 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto text-center">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-gray-900 dark:text-white mb-6 tracking-tight leading-tight">
                Find Your 
                <span class="text-primary-600 decoration-primary-300 underline decoration-4 underline-offset-4">
                    Perfect Room
                </span>
                <br class="hidden md:block" />
                in Nepal
            </h1>
            <p class="text-xl text-gray-500 dark:text-gray-400 mb-10 max-w-2xl mx-auto leading-relaxed">
                Discover comfortable and affordable rooms, flats, and apartments in your city. No hidden fees. Direct contact with owners.
            </p>
            
            <!-- Search Bar -->
            <div class="max-w-3xl mx-auto bg-white dark:bg-gray-900 p-2 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700">
                <form action="{{ route('rooms.index') }}" method="GET" class="flex flex-col md:flex-row gap-2">
                    <div class="relative flex-grow">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <input type="text" name="city" class="pl-10 w-full rounded-xl border-transparent bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white focus:border-primary-500 focus:bg-white focus:ring-0 py-3 placeholder-gray-400" placeholder="Enter city (e.g. Kathmandu, Pokhara)">
                    </div>
                    <button type="submit" class="px-8 py-3 bg-primary-600 text-white font-semibold rounded-xl hover:bg-primary-700 transition shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Search
                    </button>
                </form>
            </div>
            
            <div class="mt-8 flex flex-wrap justify-center gap-4 text-sm text-gray-500">
                <span class="mr-2">Quick Search:</span>
                <a href="{{ route('rooms.index', ['room_type' => 'single']) }}" class="text-primary-600 hover:underline">Single Room</a>
                <span class="text-gray-300">&bull;</span>
                <a href="{{ route('rooms.index', ['room_type' => 'flat']) }}" class="text-primary-600 hover:underline">Flat</a>
                <span class="text-gray-300">&bull;</span>
                <a href="{{ route('rooms.index', ['room_type' => 'apartment']) }}" class="text-primary-600 hover:underline">Apartment</a>
            </div>
        </div>
    </div>

    <!-- Latest Rooms -->
    @if($latestRooms->count() > 0)
        <div class="py-20 px-4 bg-gray-50 dark:bg-gray-900">
            <div class="max-w-7xl mx-auto">
                <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-4">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Latest Properties</h2>
                        <p class="text-gray-600 dark:text-gray-400">Freshly listed rooms and flats for you</p>
                    </div>
                    <a href="{{ route('rooms.index') }}" class="text-primary-600 hover:text-primary-700 font-semibold flex items-center gap-1">
                        View all properties
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($latestRooms as $room)
                        <x-room-card :room="$room" />
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</x-public-layout>
