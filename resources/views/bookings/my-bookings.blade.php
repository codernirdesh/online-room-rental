<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Bookings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-success-alert />

            @if($bookings->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @foreach($bookings as $booking)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition overflow-hidden">
                            <!-- Header -->
                            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <x-booking-status-badge :status="$booking->status" />
                                    @if($booking->status === 'paid')
                                        <span class="text-xs text-gray-500 dark:text-gray-400">Awaiting confirmation</span>
                                    @elseif($booking->status === 'approved')
                                        <span class="text-xs text-green-600 dark:text-green-400">Confirmed</span>
                                    @endif
                                </div>
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($booking->requested_at)->format('M d, Y') }}
                                </span>
                            </div>

                            <!-- Body -->
                            <div class="p-6">
                                <div class="flex items-start justify-between gap-4 mb-4">
                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-semibold text-gray-900 dark:text-white text-lg truncate">{{ $booking->room->title }}</h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ $booking->room->city }}, {{ $booking->room->province ?? '' }}</p>
                                    </div>
                                    <p class="text-lg font-bold text-primary-600 dark:text-primary-400 flex-shrink-0">NPR {{ number_format($booking->room->rent_price) }}</p>
                                </div>

                                @if(auth()->user()->role === 'owner' && $booking->renter)
                                    <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-3 mb-4">
                                        <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1">Renter</p>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $booking->renter->name }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $booking->renter->email }}</p>
                                        @if($booking->renter->phone)
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $booking->renter->phone }}</p>
                                        @endif
                                    </div>
                                @endif

                                @if($booking->message)
                                    <div class="mb-4">
                                        <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1">Message</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 italic">"{{ $booking->message }}"</p>
                                    </div>
                                @endif

                                <!-- Payment Info -->
                                <div class="flex items-center justify-between">
                                    <div>
                                        @if($booking->paid_at)
                                            <div class="flex items-center gap-1.5 text-green-600 dark:text-green-400">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                <span class="text-xs font-medium">Paid {{ $booking->paid_at->format('M d, Y') }}</span>
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400">Not paid</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2">
                                        @if($booking->payment_screenshot)
                                            <a href="{{ asset('storage/' . $booking->payment_screenshot) }}" target="_blank" class="inline-flex items-center gap-1 px-3 py-1.5 bg-gray-100 dark:bg-gray-700 rounded-lg text-xs text-primary-600 dark:text-primary-400 font-medium hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                Payment Proof
                                            </a>
                                        @endif
                                        <a href="{{ route('rooms.show', $booking->room) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-gray-100 dark:bg-gray-700 rounded-lg text-xs text-gray-700 dark:text-gray-300 font-medium hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                                            View Room
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Owner Actions -->
                            @if(auth()->user()->role === 'owner' && $booking->status === 'paid')
                                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900 border-t border-gray-100 dark:border-gray-700 flex gap-3">
                                    <form action="{{ route('owner.bookings.approve', $booking) }}" method="POST" class="flex-1">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition" onclick="return confirm('Approve this booking?')">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('owner.bookings.reject', $booking) }}" method="POST" class="flex-1">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition" onclick="return confirm('Reject this booking?')">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            Reject
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $bookings->links() }}
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-12 text-center">
                    <svg class="mx-auto h-16 w-16 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <p class="mt-4 text-gray-500 dark:text-gray-400">
                        {{ auth()->user()->role === 'owner' ? 'No booking requests yet.' : "You haven't made any bookings yet." }}
                    </p>
                    <a href="{{ route('rooms.index') }}" class="inline-flex items-center mt-4 px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition">
                        Browse Rooms
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
