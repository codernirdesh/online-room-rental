<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Booking Details') }}
            </h2>
            <a href="{{ route('admin.bookings.index') }}" class="text-sm text-primary-600 hover:text-primary-800 dark:text-primary-400">
                &larr; Back to Bookings
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Success/Error Messages -->
            <x-success-alert />
            @if(session('error'))
                <div class="mb-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Booking Info -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Booking Information</h3>

                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
                                <x-booking-status-badge :status="$booking->status" />
                            </div>

                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Room</p>
                                <a href="{{ route('admin.rooms.show', $booking->room) }}" class="text-primary-600 hover:text-primary-800 dark:text-primary-400 font-medium">{{ $booking->room->title }}</a>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $booking->room->address }}, {{ $booking->room->city }}</p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Rent Price</p>
                                <p class="text-lg font-bold text-primary-600 dark:text-primary-400">NPR {{ number_format($booking->room->rent_price) }}</p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Room Owner</p>
                                <p class="text-gray-900 dark:text-white font-medium">{{ $booking->room->owner->name }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $booking->room->owner->email }}</p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Renter</p>
                                <p class="text-gray-900 dark:text-white font-medium">{{ $booking->renter->name }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $booking->renter->email }}</p>
                                @if($booking->renter->phone)
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $booking->renter->phone }}</p>
                                @endif
                            </div>

                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Requested</p>
                                <p class="text-gray-900 dark:text-white">{{ $booking->requested_at->format('M d, Y H:i') }}</p>
                            </div>

                            @if($booking->paid_at)
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Paid At</p>
                                    <p class="text-gray-900 dark:text-white">{{ $booking->paid_at->format('M d, Y H:i') }}</p>
                                </div>
                            @endif

                            @if($booking->payment_method)
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Payment Method</p>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $booking->payment_method === 'esewa' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' }}">
                                        {{ $booking->payment_method === 'esewa' ? 'eSewa' : 'QR Code' }}
                                    </span>
                                </div>
                            @endif

                            @if($booking->esewa_transaction_id)
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">eSewa Transaction ID</p>
                                    <p class="text-gray-900 dark:text-white font-mono text-sm">{{ $booking->esewa_transaction_id }}</p>
                                </div>
                            @endif

                            @if($booking->esewa_ref_id)
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">eSewa Reference ID</p>
                                    <p class="text-gray-900 dark:text-white font-mono text-sm">{{ $booking->esewa_ref_id }}</p>
                                </div>
                            @endif

                            @if($booking->esewa_amount)
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">eSewa Amount</p>
                                    <p class="text-gray-900 dark:text-white font-medium">NPR {{ number_format($booking->esewa_amount) }}</p>
                                </div>
                            @endif

                            @if($booking->message)
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Message from Renter</p>
                                    <p class="text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-900 rounded-lg p-3 text-sm">{{ $booking->message }}</p>
                                </div>
                            @endif
                        </div>

                        <!-- Actions -->
                        @if($booking->status === 'paid')
                            <div class="mt-6 flex gap-3">
                                <form action="{{ route('admin.bookings.approve', $booking) }}" method="POST" class="flex-1">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition" onclick="return confirm('Approve this booking? You are confirming the payment is valid.')">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        Approve Booking
                                    </button>
                                </form>
                                <form action="{{ route('admin.bookings.reject', $booking) }}" method="POST" class="flex-1">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition" onclick="return confirm('Reject this booking? The room will become available again.')">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        Reject Booking
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Payment Proof -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Payment Proof</h3>

                        @if($booking->payment_method === 'esewa' && $booking->esewa_ref_id)
                            <div class="text-center py-8 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                <svg class="mx-auto h-12 w-12 text-green-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                                <p class="text-sm font-medium text-green-700 dark:text-green-300">Paid via eSewa</p>
                                <p class="text-xs text-green-600 dark:text-green-400 mt-1">Payment verified electronically</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 font-mono">Ref: {{ $booking->esewa_ref_id }}</p>
                            </div>
                        @elseif($booking->payment_screenshot)
                            <div class="rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                                <img
                                    src="{{ asset('storage/' . $booking->payment_screenshot) }}"
                                    alt="Payment Screenshot"
                                    class="w-full object-contain max-h-[500px] bg-gray-50 dark:bg-gray-900 cursor-pointer"
                                    onclick="window.open(this.src, '_blank')"
                                    title="Click to view full size"
                                >
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 text-center">Click image to view full size</p>
                        @else
                            <div class="text-center py-12 bg-gray-50 dark:bg-gray-900 rounded-lg">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No payment screenshot uploaded</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
