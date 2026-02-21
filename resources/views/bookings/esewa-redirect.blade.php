<x-public-layout :title="'Redirecting to eSewa'" activePage="rooms">
    <div class="pt-24 pb-16 px-4">
        <div class="max-w-lg mx-auto text-center">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                <div class="mb-6">
                    <svg class="w-16 h-16 mx-auto text-green-500 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Redirecting to eSewa...</h2>
                <p class="text-gray-500 dark:text-gray-400 mb-4">You are being redirected to eSewa to complete your payment of <strong class="text-primary-600">NPR {{ number_format($room->rent_price) }}</strong>.</p>
                <p class="text-sm text-gray-400 dark:text-gray-500">If you are not redirected automatically, click the button below.</p>

                <form id="esewa-form" action="{{ $paymentUrl }}" method="POST" class="mt-6">
                    @foreach($paymentData as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                    <button type="submit" class="px-8 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition font-semibold">
                        Pay with eSewa
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Auto-submit the form after a short delay
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                document.getElementById('esewa-form').submit();
            }, 1500);
        });
    </script>
</x-public-layout>
