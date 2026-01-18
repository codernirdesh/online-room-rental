<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $room->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Room Details -->
                    <div class="mb-6">
                        <h3 class="text-2xl font-bold mb-4">{{ $room->title }}</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <p class="text-gray-600 dark:text-gray-400 mb-2"><strong>Description:</strong></p>
                                <p>{{ $room->description }}</p>
                            </div>
                            
                            <div>
                                <p class="text-gray-600 dark:text-gray-400 mb-2"><strong>Rent Price:</strong></p>
                                <p class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">NPR {{ number_format($room->rent_price, 2) }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <p class="text-gray-600 dark:text-gray-400 mb-1"><strong>Address:</strong></p>
                                <p>{{ $room->address }}</p>
                            </div>
                            
                            <div>
                                <p class="text-gray-600 dark:text-gray-400 mb-1"><strong>Location:</strong></p>
                                <p>{{ $room->city }}, {{ $room->province }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div>
                                <p class="text-gray-600 dark:text-gray-400 mb-1"><strong>Room Type:</strong></p>
                                <p class="capitalize">{{ $room->room_type }}</p>
                            </div>
                            
                            <div>
                                <p class="text-gray-600 dark:text-gray-400 mb-1"><strong>Status:</strong></p>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    @if($room->status === 'available') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                    @elseif($room->status === 'booked') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                    @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                                    @endif">
                                    {{ ucfirst($room->status) }}
                                </span>
                            </div>
                            
                            <div>
                                <p class="text-gray-600 dark:text-gray-400 mb-1"><strong>Available From:</strong></p>
                                <p>{{ \Carbon\Carbon::parse($room->available_from)->format('M d, Y') }}</p>
                            </div>
                        </div>

                        <div class="mb-6">
                            <p class="text-gray-600 dark:text-gray-400 mb-1"><strong>Amenities:</strong></p>
                            <p>{{ $room->amenities }}</p>
                        </div>
                    </div>

                    <!-- Owner Contact (for logged-in users only) -->
                    @auth
                        <div class="mb-6 p-4 bg-gray-100 dark:bg-gray-700 rounded-lg">
                            <h4 class="text-lg font-semibold mb-2">Owner Contact</h4>
                            <p><strong>Name:</strong> {{ $room->owner->name }}</p>
                            <p><strong>Email:</strong> {{ $room->owner->email }}</p>
                            @if($room->owner->phone)
                                <p><strong>Phone:</strong> {{ $room->owner->phone }}</p>
                            @endif
                        </div>
                    @endauth

                    <!-- Booking Form (for renters only) -->
                    @auth
                        @if(auth()->user()->role === 'renter' && $room->status === 'available')
                            <div class="mt-8 p-6 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg">
                                <h4 class="text-xl font-semibold mb-4">Request Booking</h4>
                                <form action="{{ route('bookings.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="room_id" value="{{ $room->id }}">
                                    
                                    <div class="mb-4">
                                        <label for="message" class="block text-sm font-medium mb-2">Message (Optional)</label>
                                        <textarea 
                                            name="message" 
                                            id="message" 
                                            rows="4" 
                                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600"
                                            placeholder="Add any message or questions for the owner...">{{ old('message') }}</textarea>
                                        @error('message')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <button 
                                        type="submit" 
                                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200">
                                        Submit Booking Request
                                    </button>
                                </form>
                            </div>
                        @elseif(auth()->user()->role === 'renter' && $room->status !== 'available')
                            <div class="mt-8 p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                                <p class="text-yellow-800 dark:text-yellow-200">This room is currently not available for booking.</p>
                            </div>
                        @endif
                    @else
                        <div class="mt-8 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <p class="text-blue-800 dark:text-blue-200">
                                Please <a href="{{ route('login') }}" class="font-semibold underline">login</a> to view owner contact details and request a booking.
                            </p>
                        </div>
                    @endauth

                    <!-- Back Button -->
                    <div class="mt-6">
                        <a href="{{ route('rooms.index') }}" class="inline-flex items-center text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Back to Room Listings
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
