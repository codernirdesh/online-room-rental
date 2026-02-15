<x-public-layout :title="'Checkout - ' . $room->title" activePage="rooms">
    <div class="pt-24 pb-16 px-4">
        <div class="max-w-4xl mx-auto">
            <!-- Back Button -->
            <a href="{{ route('rooms.show', $room) }}" class="inline-flex items-center text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition mb-8">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Room
            </a>

            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">Checkout & Payment</h1>

            {{-- Error Messages --}}
            @if(session('error'))
                <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 px-6 py-4 rounded-xl">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Room Summary -->
                <div class="space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden">
                        @if($room->image)
                            <img src="{{ asset('storage/' . $room->image) }}" alt="{{ $room->title }}" class="w-full h-48 object-cover">
                        @else
                            <x-room-image-placeholder class="w-full h-48" />
                        @endif
                        
                        <div class="p-6">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ $room->title }}</h2>
                            <div class="flex items-center text-gray-600 dark:text-gray-400 text-sm mb-4">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                {{ $room->address }}, {{ $room->city }}
                            </div>
                            
                            <div class="border-t border-gray-200 dark:border-gray-700 pt-4 space-y-3">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500 dark:text-gray-400">Room Type</span>
                                    <span class="text-gray-900 dark:text-white font-medium capitalize">{{ $room->room_type }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500 dark:text-gray-400">Owner</span>
                                    <span class="text-gray-900 dark:text-white font-medium">{{ $room->owner->name }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500 dark:text-gray-400">Available From</span>
                                    <span class="text-gray-900 dark:text-white font-medium">{{ \Carbon\Carbon::parse($room->available_from)->format('M d, Y') }}</span>
                                </div>
                                <div class="border-t border-gray-200 dark:border-gray-700 pt-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-lg font-semibold text-gray-900 dark:text-white">Total Amount</span>
                                        <span class="text-2xl font-bold text-primary-600 dark:text-primary-400">NPR {{ number_format($room->rent_price) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Instructions -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-2xl p-6">
                        <h3 class="text-lg font-bold text-blue-800 dark:text-blue-200 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            How to Pay
                        </h3>
                        <ol class="list-decimal list-inside space-y-2 text-blue-700 dark:text-blue-300 text-sm">
                            <li>Scan the QR code shown on the right using your mobile banking app (eSewa, Khalti, etc.)</li>
                            <li>Pay the exact amount: <strong>NPR {{ number_format($room->rent_price) }}</strong></li>
                            <li>Take a <strong>screenshot</strong> of the successful payment</li>
                            <li>Upload the screenshot below and submit</li>
                            <li>Wait for the owner to verify and confirm your booking</li>
                        </ol>
                    </div>
                </div>

                <!-- QR Code & Upload Form -->
                <div class="space-y-6">
                    <!-- QR Code Display -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 text-center">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Scan QR to Pay</h3>
                        <div class="inline-block bg-white p-4 rounded-xl shadow-inner mb-4">
                            @if($paymentQr)
                                <img src="{{ asset('storage/' . $paymentQr) }}" alt="Payment QR Code" class="w-64 h-64 object-contain mx-auto">
                            @else
                                {{-- Placeholder QR if no image is set --}}
                                <div class="w-64 h-64 bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg flex flex-col items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                                    </svg>
                                    <p class="text-gray-500 text-sm">QR Code</p>
                                    <p class="text-gray-400 text-xs">Contact admin to set up</p>
                                </div>
                            @endif
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">
                            Scan with eSewa, Khalti, or any mobile banking app
                        </p>
                        <div class="mt-3 bg-primary-50 dark:bg-primary-900/20 rounded-lg px-4 py-2 inline-block">
                            <span class="text-primary-700 dark:text-primary-300 font-semibold">
                                Amount: NPR {{ number_format($room->rent_price) }}
                            </span>
                        </div>
                    </div>

                    <!-- Upload Payment Screenshot Form -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Upload Payment Proof</h3>
                        
                        <form action="{{ route('bookings.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                            @csrf
                            <input type="hidden" name="room_id" value="{{ $room->id }}">

                            <!-- Payment Screenshot Upload -->
                            <div>
                                <label for="payment_screenshot" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    Payment Screenshot <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1">
                                    <div id="drop-zone" class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl px-6 py-8 text-center hover:border-primary-500 dark:hover:border-primary-500 transition cursor-pointer">
                                        <input type="file" name="payment_screenshot" id="payment_screenshot" accept="image/*" class="hidden" required>
                                        <div id="upload-prompt">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                                <span class="font-semibold text-primary-600 dark:text-primary-400">Click to upload</span> or drag & drop
                                            </p>
                                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-500">PNG, JPG, JPEG, WEBP up to 5MB</p>
                                        </div>
                                        <div id="preview-container" class="hidden">
                                            <img id="preview-image" src="" alt="Preview" class="max-h-40 mx-auto rounded-lg shadow">
                                            <p id="file-name" class="mt-2 text-sm text-gray-600 dark:text-gray-400"></p>
                                            <button type="button" id="remove-file" class="mt-2 text-xs text-red-500 hover:text-red-700 font-medium">Remove</button>
                                        </div>
                                    </div>
                                </div>
                                @error('payment_screenshot')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Optional Message -->
                            <div>
                                <label for="message" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    Message (Optional)
                                </label>
                                <textarea 
                                    name="message" 
                                    id="message" 
                                    rows="3" 
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                    placeholder="Any additional notes for the owner...">{{ old('message') }}</textarea>
                                @error('message')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <button 
                                type="submit" 
                                class="w-full px-6 py-4 bg-green-600 text-white rounded-xl hover:bg-green-700 hover:shadow-xl transition font-semibold text-lg flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Submit Payment & Book Room
                            </button>

                            <p class="text-xs text-gray-500 dark:text-gray-400 text-center">
                                By submitting, you confirm that you have made the payment. Your booking will be confirmed after the owner verifies the payment.
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- JavaScript for file upload preview --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropZone = document.getElementById('drop-zone');
            const fileInput = document.getElementById('payment_screenshot');
            const uploadPrompt = document.getElementById('upload-prompt');
            const previewContainer = document.getElementById('preview-container');
            const previewImage = document.getElementById('preview-image');
            const fileName = document.getElementById('file-name');
            const removeBtn = document.getElementById('remove-file');

            // Click to open file dialog
            dropZone.addEventListener('click', function(e) {
                if (e.target !== removeBtn && !removeBtn.contains(e.target)) {
                    fileInput.click();
                }
            });

            // Handle file selection
            fileInput.addEventListener('change', function() {
                handleFile(this.files[0]);
            });

            // Drag and drop
            dropZone.addEventListener('dragover', function(e) {
                e.preventDefault();
                dropZone.classList.add('border-primary-500', 'bg-primary-50', 'dark:bg-primary-900/10');
            });

            dropZone.addEventListener('dragleave', function(e) {
                e.preventDefault();
                dropZone.classList.remove('border-primary-500', 'bg-primary-50', 'dark:bg-primary-900/10');
            });

            dropZone.addEventListener('drop', function(e) {
                e.preventDefault();
                dropZone.classList.remove('border-primary-500', 'bg-primary-50', 'dark:bg-primary-900/10');
                if (e.dataTransfer.files.length) {
                    fileInput.files = e.dataTransfer.files;
                    handleFile(e.dataTransfer.files[0]);
                }
            });

            // Remove file
            removeBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                fileInput.value = '';
                uploadPrompt.classList.remove('hidden');
                previewContainer.classList.add('hidden');
            });

            function handleFile(file) {
                if (!file) return;
                
                const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
                if (!validTypes.includes(file.type)) {
                    alert('Please upload a valid image file (PNG, JPG, JPEG, or WEBP).');
                    return;
                }

                if (file.size > 5 * 1024 * 1024) {
                    alert('File size must be under 5MB.');
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    fileName.textContent = file.name;
                    uploadPrompt.classList.add('hidden');
                    previewContainer.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</x-public-layout>
