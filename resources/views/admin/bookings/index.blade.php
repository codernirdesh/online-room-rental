<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manage Bookings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success/Error Messages -->
            <x-success-alert />
            @if(session('error'))
                <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 px-5 py-4 rounded-xl">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Filter Form -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-xl mb-8">
                <div class="p-6">
                    <form action="{{ route('admin.bookings.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
                        <div class="flex-1 min-w-[200px]">
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Filter by Status</label>
                            <select name="status" id="status" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending (Awaiting Payment)</option>
                                <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid (Awaiting Approval)</option>
                                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <div>
                            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-medium py-2.5 px-6 rounded-lg transition">
                                Filter
                            </button>
                        </div>
                        @if(request('status'))
                            <div>
                                <a href="{{ route('admin.bookings.index') }}" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                    Clear filter
                                </a>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Bookings Grid -->
            @if($bookings->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @foreach($bookings as $booking)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition overflow-hidden {{ $booking->status === 'paid' ? 'ring-2 ring-blue-200 dark:ring-blue-800' : ($booking->status === 'pending' ? 'ring-2 ring-yellow-200 dark:ring-yellow-800' : '') }}">
                            <!-- Header -->
                            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <span class="text-sm font-mono text-gray-500 dark:text-gray-400">#{{ $booking->id }}</span>
                                    <x-booking-status-badge :status="$booking->status" />
                                </div>
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('admin.bookings.show', $booking) }}" class="text-xs text-primary-600 hover:text-primary-800 dark:text-primary-400 font-medium">
                                        View Details
                                    </a>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ \Carbon\Carbon::parse($booking->requested_at)->format('M d, Y') }}
                                    </span>
                                </div>
                            </div>

                            <!-- Body -->
                            <div class="p-6">
                                <div class="grid grid-cols-2 gap-6 mb-5">
                                    <!-- Room Info -->
                                    <div>
                                        <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1.5">Room</p>
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ $booking->room->title }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ $booking->room->city }}</p>
                                        <p class="text-sm font-bold text-primary-600 dark:text-primary-400 mt-1">NPR {{ number_format($booking->room->rent_price) }}</p>
                                    </div>

                                    <!-- Renter Info -->
                                    <div>
                                        <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1.5">Renter</p>
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ $booking->renter->name }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ $booking->renter->email }}</p>
                                        @if($booking->renter->phone)
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $booking->renter->phone }}</p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Payment Info -->
                                <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4 mb-5">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1">Payment</p>
                                            @if($booking->paid_at)
                                                <div class="flex items-center gap-1.5 text-green-600 dark:text-green-400">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    <span class="text-sm font-medium">Paid on {{ $booking->paid_at->format('M d, Y \a\t H:i') }}</span>
                                                </div>
                                            @else
                                                <span class="text-sm text-gray-400">No payment recorded</span>
                                            @endif
                                        </div>
                                        @if($booking->payment_screenshot)
                                            <a href="{{ asset('storage/' . $booking->payment_screenshot) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg text-sm text-primary-600 hover:text-primary-800 dark:text-primary-400 font-medium hover:shadow-sm transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                View Proof
                                            </a>
                                        @endif
                                    </div>
                                </div>

                                @if($booking->message)
                                    <div class="mb-5">
                                        <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1.5">Message</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 italic">"{{ $booking->message }}"</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Actions Footer -->
                            @if($booking->status === 'paid')
                                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900 border-t border-gray-100 dark:border-gray-700 flex gap-3">
                                    <form action="{{ route('admin.bookings.approve', $booking) }}" method="POST" class="flex-1">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition" onclick="return confirm('Approve this booking? Payment will be marked as verified.')">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.bookings.reject', $booking) }}" method="POST" class="flex-1">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition" onclick="return confirm('Reject this booking?')">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            Reject
                                        </button>
                                    </form>
                                </div>
                            @elseif($booking->status === 'approved')
                                <div class="px-6 py-3 bg-green-50 dark:bg-green-900/10 border-t border-green-100 dark:border-green-900/20">
                                    <p class="text-sm text-green-700 dark:text-green-400 font-medium flex items-center gap-1.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Booking Confirmed
                                    </p>
                                </div>
                            @elseif($booking->status === 'pending')
                                <div class="px-6 py-3 bg-yellow-50 dark:bg-yellow-900/10 border-t border-yellow-100 dark:border-yellow-900/20">
                                    <p class="text-sm text-yellow-700 dark:text-yellow-400 font-medium flex items-center gap-1.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Awaiting Payment from Renter
                                    </p>
                                </div>
                            @elseif($booking->status === 'rejected')
                                <div class="px-6 py-3 bg-red-50 dark:bg-red-900/10 border-t border-red-100 dark:border-red-900/20">
                                    <p class="text-sm text-red-700 dark:text-red-400 font-medium">Booking Rejected</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $bookings->links() }}
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-12 text-center">
                    <svg class="mx-auto h-16 w-16 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <p class="mt-4 text-gray-500 dark:text-gray-400">No bookings found.</p>
                    @if(request('status'))
                        <a href="{{ route('admin.bookings.index') }}" class="mt-2 inline-block text-sm text-primary-600 hover:text-primary-800 dark:text-primary-400">
                            Clear filters to see all bookings
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
