@props(['room'])

<a href="{{ route('rooms.show', $room) }}" class="group block h-full">
    <div class="bg-white dark:bg-gray-800 rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 dark:border-gray-700 h-full flex flex-col">
        <div class="relative h-64 overflow-hidden bg-gray-200">
            @if($room->image)
                <img src="{{ asset('storage/' . $room->image) }}" alt="{{ $room->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            @else
                <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 22V12h6v10"/>
                    </svg>
                </div>
            @endif
            <div class="absolute top-4 right-4 px-3 py-1 bg-white/95 backdrop-blur-sm rounded-lg text-xs font-bold text-gray-900 shadow-sm uppercase tracking-wide">
                {{ ucfirst($room->room_type) }}
            </div>
            @if($room->status === 'available')
                <div class="absolute top-4 left-4 px-3 py-1 bg-green-500 text-white rounded-lg text-xs font-bold shadow-sm">
                    Available
                </div>
            @endif
            <div class="absolute bottom-4 left-4">
                <span class="px-3 py-1 bg-primary-600 text-white rounded-lg text-sm font-bold shadow-sm">
                    NPR {{ number_format($room->rent_price) }}
                </span>
            </div>
        </div>
        <div class="p-6 flex-grow flex flex-col">
            <div class="mb-4">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 group-hover:text-primary-600 transition line-clamp-1">
                    {{ $room->title }}
                </h3>
                <div class="flex items-center text-gray-500 dark:text-gray-400 text-sm">
                    <svg class="w-4 h-4 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="truncate">{{ $room->city }}, {{ $room->province }}</span>
                </div>
            </div>
            
            <div class="mt-auto pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center text-sm">
                <span class="text-gray-500 dark:text-gray-400">
                    {{ $room->created_at->diffForHumans() }}
                </span>
                <span class="text-primary-600 font-semibold group-hover:translate-x-1 transition-transform inline-flex items-center">
                    View Details
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </span>
            </div>
        </div>
    </div>
</a>
