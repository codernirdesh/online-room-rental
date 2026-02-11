<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $room->title }}
            </h2>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.rooms.edit', $room) }}" class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-md transition">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Edit
                </a>
                <a href="{{ route('admin.rooms.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400">
                    &larr; Back to Rooms
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-success-alert />

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Room Details -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        @if($room->image)
                            <img src="{{ asset('storage/' . $room->image) }}" alt="{{ $room->title }}" class="w-full h-48 object-cover">
                        @else
                            <x-room-image-placeholder class="w-full h-48" />
                        @endif
                        <div class="p-6 space-y-4">
                            <div class="flex items-center justify-between">
                                <x-room-status-badge :status="$room->status" class="px-3 py-1" />
                                <span class="text-lg font-bold text-primary-600 dark:text-primary-400">NPR {{ number_format($room->rent_price) }}</span>
                            </div>

                            <div class="space-y-3 text-sm">
                                <div>
                                    <span class="text-gray-500 dark:text-gray-400">Owner</span>
                                    <p class="text-gray-900 dark:text-white font-medium">{{ $room->owner->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $room->owner->email }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-500 dark:text-gray-400">Location</span>
                                    <p class="text-gray-900 dark:text-white font-medium">{{ $room->address }}, {{ $room->city }}, {{ $room->province }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-500 dark:text-gray-400">Room Type</span>
                                    <p class="text-gray-900 dark:text-white font-medium capitalize">{{ $room->room_type }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-500 dark:text-gray-400">Available From</span>
                                    <p class="text-gray-900 dark:text-white font-medium">{{ \Carbon\Carbon::parse($room->available_from)->format('M d, Y') }}</p>
                                </div>
                                @if($room->amenities)
                                    <div>
                                        <span class="text-gray-500 dark:text-gray-400">Amenities</span>
                                        <p class="text-gray-900 dark:text-white">{{ $room->amenities }}</p>
                                    </div>
                                @endif
                                <div>
                                    <span class="text-gray-500 dark:text-gray-400">Description</span>
                                    <p class="text-gray-700 dark:text-gray-300">{{ $room->description }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Stats Summary -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4">Booking Summary</h3>
                        <div class="grid grid-cols-3 gap-4">
                            <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-3 text-center">
                                <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $bookings->where('status', 'pending')->count() }}</p>
                                <p class="text-xs text-yellow-600 dark:text-yellow-400">Awaiting Payment</p>
                            </div>
                            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-3 text-center">
                                <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $bookings->where('status', 'paid')->count() }}</p>
                                <p class="text-xs text-blue-600 dark:text-blue-400">Awaiting Approval</p>
                            </div>
                            <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-3 text-center">
                                <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $bookings->where('status', 'approved')->count() }}</p>
                                <p class="text-xs text-green-600 dark:text-green-400">Confirmed</p>
                            </div>
                            <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-3 text-center">
                                <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $bookings->where('status', 'rejected')->count() }}</p>
                                <p class="text-xs text-red-600 dark:text-red-400">Rejected</p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-3 text-center">
                                <p class="text-2xl font-bold text-gray-600 dark:text-gray-400">{{ $bookings->count() }}</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">Total</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bookings for this Room -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Bookings for this Room</h3>

                            @if($bookings->count() > 0)
                                <div class="space-y-4">
                                    @foreach($bookings as $booking)
                                        <div class="border border-gray-200 dark:border-gray-700 rounded-xl p-5 hover:shadow-md transition {{ $booking->status === 'pending' ? 'border-l-4 border-l-yellow-500' : ($booking->status === 'paid' ? 'border-l-4 border-l-blue-500' : ($booking->status === 'approved' ? 'border-l-4 border-l-green-500' : '')) }}">
                                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                                                <!-- Renter Info -->
                                                <div class="flex-1">
                                                    <div class="flex items-center gap-3 mb-2">
                                                        <div class="w-10 h-10 bg-primary-100 dark:bg-primary-900/30 rounded-full flex items-center justify-center">
                                                            <span class="text-primary-600 dark:text-primary-400 font-bold text-sm">{{ strtoupper(substr($booking->renter->name, 0, 1)) }}</span>
                                                        </div>
                                                        <div>
                                                            <p class="font-semibold text-gray-900 dark:text-white">{{ $booking->renter->name }}</p>
                                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $booking->renter->email }}{{ $booking->renter->phone ? ' · ' . $booking->renter->phone : '' }}</p>
                                                        </div>
                                                    </div>

                                                    @if($booking->message)
                                                        <p class="text-sm text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-900 rounded-lg p-3 mt-2">
                                                            "{{ $booking->message }}"
                                                        </p>
                                                    @endif

                                                    <div class="flex items-center gap-4 mt-3 text-xs text-gray-500 dark:text-gray-400">
                                                        <span>Requested: {{ $booking->requested_at->format('M d, Y H:i') }}</span>
                                                        @if($booking->paid_at)
                                                            <span>Paid: {{ $booking->paid_at->format('M d, Y H:i') }}</span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <!-- Status & Actions -->
                                                <div class="flex flex-col items-end gap-3">
                                                    <x-booking-status-badge :status="$booking->status" />

                                                    @if($booking->payment_screenshot)
                                                        <a href="{{ route('admin.bookings.show', $booking) }}" class="inline-flex items-center gap-1 text-xs text-primary-600 hover:text-primary-800 dark:text-primary-400 font-medium">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                            View Payment
                                                        </a>
                                                    @endif

                                                    @if($booking->status === 'paid')
                                                        <div class="flex gap-2">
                                                            <form action="{{ route('admin.bookings.approve', $booking) }}" method="POST">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-lg transition" onclick="return confirm('Approve this booking?')">
                                                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                                    Approve
                                                                </button>
                                                            </form>
                                                            <form action="{{ route('admin.bookings.reject', $booking) }}" method="POST">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded-lg transition" onclick="return confirm('Reject this booking?')">
                                                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                                    Reject
                                                                </button>
                                                            </form>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No bookings for this room yet.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
