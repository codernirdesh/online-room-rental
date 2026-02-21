<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <x-success-alert />

            <!-- eSewa Payment Settings -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-xl">
                <div class="p-8">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                            eSewa Payment Gateway
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Configure eSewa online payment integration. When enabled, renters can pay directly via eSewa.</p>
                    </div>

                    <form method="POST" action="{{ route('admin.settings.update-esewa') }}" class="space-y-5">
                        @csrf

                        <!-- Enable/Disable Toggle -->
                        <div>
                            <label for="esewa_enabled" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">eSewa Payment</label>
                            <select id="esewa_enabled" name="esewa_enabled"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500">
                                <option value="0" {{ ($esewaEnabled ?? '0') == '0' ? 'selected' : '' }}>Disabled</option>
                                <option value="1" {{ ($esewaEnabled ?? '0') == '1' ? 'selected' : '' }}>Enabled</option>
                            </select>
                            @error('esewa_enabled')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Merchant Code -->
                        <div>
                            <label for="esewa_merchant_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Merchant Code (Product Code)</label>
                            <input type="text" id="esewa_merchant_code" name="esewa_merchant_code"
                                value="{{ old('esewa_merchant_code', $esewaMerchantCode ?? 'EPAYTEST') }}"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500"
                                placeholder="EPAYTEST">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Use 'EPAYTEST' for testing. Replace with your eSewa merchant code for production.</p>
                            @error('esewa_merchant_code')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Environment -->
                        <div>
                            <label for="esewa_environment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Environment</label>
                            <select id="esewa_environment" name="esewa_environment"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500">
                                <option value="testing" {{ ($esewaEnvironment ?? 'testing') == 'testing' ? 'selected' : '' }}>Testing (Sandbox)</option>
                                <option value="production" {{ ($esewaEnvironment ?? 'testing') == 'production' ? 'selected' : '' }}>Production (Live)</option>
                            </select>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Use 'Testing' for development. Switch to 'Production' when going live.</p>
                            @error('esewa_environment')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition shadow-sm">
                                Save eSewa Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Payment QR Code Settings -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-xl">
                <div class="p-8">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Payment QR Code</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Upload or update the payment QR code that renters will scan to make payments.</p>
                    </div>

                    <!-- Current QR Code -->
                    <div class="mb-6">
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Current QR Code</p>
                        <div class="inline-block bg-white p-4 rounded-xl shadow-inner border border-gray-200 dark:border-gray-600">
                            @if($paymentQr)
                                <img src="{{ asset('storage/' . $paymentQr) }}" alt="Payment QR Code" class="w-64 h-64 object-contain">
                            @else
                                <div class="w-64 h-64 bg-gray-100 dark:bg-gray-700 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg flex flex-col items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                                    </svg>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm">No QR code uploaded</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Upload Form -->
                    <form method="POST" action="{{ route('admin.settings.update-payment-qr') }}" enctype="multipart/form-data" class="space-y-5">
                        @csrf

                        <div>
                            <label for="payment_qr" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Upload New QR Code</label>
                            <input type="file" id="payment_qr" name="payment_qr" accept="image/*" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">PNG, JPG, JPEG, WEBP up to 5MB</p>
                            @error('payment_qr')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition shadow-sm">
                                Update QR Code
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
