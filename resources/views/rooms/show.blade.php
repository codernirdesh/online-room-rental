<x-public-layout :title="$room->title . ' - RoomRent'" activePage="rooms">
    <div class="pt-24 pb-16 px-4">
        <div class="max-w-7xl mx-auto">
            <!-- Back Button -->
            <a href="{{ route('rooms.index') }}" class="inline-flex items-center text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition mb-8">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Rooms
            </a>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Image -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl overflow-hidden shadow-xl">
                        @if($room->image)
                            <img src="{{ asset('storage/' . $room->image) }}" alt="{{ $room->title }}" class="w-full h-96 object-cover">
                        @else
                            <x-room-image-placeholder class="w-full h-96" />
                        @endif
                    </div>

                    <!-- Room Details -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                        <div class="flex items-start justify-between mb-6">
                            <div>
                                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-2">{{ $room->title }}</h1>
                                <div class="flex items-center text-gray-600 dark:text-gray-400">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ $room->address }}, {{ $room->city }}, {{ $room->province }}
                                </div>
                            </div>
                            <x-room-status-badge :status="$room->status" class="px-4 py-2 text-sm" />
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-3 gap-6 mb-8">
                            <div>
                                <p class="text-gray-500 dark:text-gray-400 text-sm mb-1">Room Type</p>
                                <p class="text-gray-900 dark:text-white font-semibold capitalize">{{ $room->room_type }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 dark:text-gray-400 text-sm mb-1">Available From</p>
                                <p class="text-gray-900 dark:text-white font-semibold">{{ \Carbon\Carbon::parse($room->available_from)->format('M d, Y') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 dark:text-gray-400 text-sm mb-1">Price</p>
                                <p class="text-2xl font-bold text-primary-600 dark:text-primary-400">NPR {{ number_format($room->rent_price) }}</p>
                            </div>
                        </div>

                        <div class="mb-8">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Description</h3>
                            <p class="text-gray-600 dark:text-gray-400 leading-relaxed">{{ $room->description }}</p>
                        </div>

                        <div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Amenities</h3>
                            <p class="text-gray-600 dark:text-gray-400 leading-relaxed">{{ $room->amenities }}</p>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Owner Contact -->
                    @auth
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Owner Details</h3>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm">Name</p>
                                    <p class="text-gray-900 dark:text-white font-medium">{{ $room->owner->name }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm">Email</p>
                                    <a href="mailto:{{ $room->owner->email }}" class="text-primary-600 dark:text-primary-400 hover:underline">{{ $room->owner->email }}</a>
                                </div>
                                @if($room->owner->phone)
                                    <div>
                                        <p class="text-gray-500 dark:text-gray-400 text-sm">Phone</p>
                                        <a href="tel:{{ $room->owner->phone }}" class="text-primary-600 dark:text-primary-400 hover:underline">{{ $room->owner->phone }}</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endauth

                    <!-- Booking Form -->
                    @auth
                        @if(auth()->user()->role === 'renter' && isset($userBooking))
                            {{-- User already booked this room --}}
                            <div class="bg-green-50 dark:bg-green-900/20 rounded-2xl shadow-xl p-6">
                                <div class="text-center">
                                    <div class="w-16 h-16 bg-green-100 dark:bg-green-900/40 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-bold text-green-800 dark:text-green-200 mb-2">Already Booked</h3>
                                    <p class="text-green-700 dark:text-green-300 text-sm mb-1">You have already booked this room.</p>
                                    <x-booking-status-badge :status="$userBooking->status" class="mt-2" />
                                    @if($userBooking->status === 'paid')
                                        <p class="text-green-600 dark:text-green-400 text-xs mt-2">Waiting for owner confirmation</p>
                                    @elseif($userBooking->status === 'approved')
                                        <p class="text-green-600 dark:text-green-400 text-xs mt-2">Your booking is confirmed!</p>
                                    @endif
                                    <a href="{{ route('my-bookings') }}" class="inline-block mt-4 text-sm text-primary-600 dark:text-primary-400 hover:underline font-medium">
                                        View My Bookings &rarr;
                                    </a>
                                </div>
                            </div>
                        @elseif(auth()->user()->role === 'renter' && $room->isBookable())
                            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Book This Room</h3>
                                <div class="mb-4">
                                    <div class="flex justify-between text-sm mb-2">
                                        <span class="text-gray-500 dark:text-gray-400">Rent Price</span>
                                        <span class="text-gray-900 dark:text-white font-bold">NPR {{ number_format($room->rent_price) }}</span>
                                    </div>
                                </div>
                                <a 
                                    href="{{ route('bookings.checkout', $room) }}" 
                                    class="block w-full text-center px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 hover:shadow-xl transition font-semibold">
                                    Proceed to Pay & Book
                                </a>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-3 text-center">
                                    You'll be redirected to the payment page
                                </p>
                            </div>
                        @elseif(auth()->user()->role === 'renter' && !$room->isBookable())
                            <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-2xl p-6">
                                <div class="flex items-center gap-3">
                                    <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                    </svg>
                                    <p class="text-yellow-800 dark:text-yellow-200 font-medium">This room is currently not available for booking.</p>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Interested?</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">
                                Please login to view owner details and request a booking.
                            </p>
                            <a href="{{ route('login') }}" class="block w-full text-center px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 hover:shadow-xl transition font-semibold">
                                Login to Book
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</x-public-layout>
