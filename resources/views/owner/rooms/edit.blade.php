<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Room') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('owner.rooms.update', $room) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Title -->
                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Room Title <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="title" 
                                id="title" 
                                value="{{ old('title', $room->title) }}"
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600"
                                required>
                            @error('title')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Description <span class="text-red-500">*</span>
                            </label>
                            <textarea 
                                name="description" 
                                id="description" 
                                rows="4"
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600"
                                required>{{ old('description', $room->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div class="mb-4">
                            <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Address <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="address" 
                                id="address" 
                                value="{{ old('address', $room->address) }}"
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600"
                                required>
                            @error('address')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- City and Province -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    City <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="city" 
                                    id="city" 
                                    value="{{ old('city', $room->city) }}"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600"
                                    required>
                                @error('city')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="province" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Province <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="province" 
                                    id="province" 
                                    value="{{ old('province', $room->province) }}"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600"
                                    required>
                                @error('province')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Rent Price and Room Type -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="rent_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Rent Price (NPR) <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="number" 
                                    name="rent_price" 
                                    id="rent_price" 
                                    value="{{ old('rent_price', $room->rent_price) }}"
                                    step="0.01"
                                    min="0"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600"
                                    required>
                                @error('rent_price')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="room_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Room Type <span class="text-red-500">*</span>
                                </label>
                                <select 
                                    name="room_type" 
                                    id="room_type"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600"
                                    required>
                                    <option value="">Select Type</option>
                                    <option value="single" {{ old('room_type', $room->room_type) === 'single' ? 'selected' : '' }}>Single</option>
                                    <option value="double" {{ old('room_type', $room->room_type) === 'double' ? 'selected' : '' }}>Double</option>
                                    <option value="flat" {{ old('room_type', $room->room_type) === 'flat' ? 'selected' : '' }}>Flat</option>
                                    <option value="apartment" {{ old('room_type', $room->room_type) === 'apartment' ? 'selected' : '' }}>Apartment</option>
                                </select>
                                @error('room_type')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Amenities -->
                        <div class="mb-4">
                            <label for="amenities" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Amenities <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="amenities" 
                                id="amenities" 
                                value="{{ old('amenities', $room->amenities) }}"
                                placeholder="e.g., WiFi, Parking, 24/7 Water"
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600"
                                required>
                            @error('amenities')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Available From -->
                        <div class="mb-4">
                            <label for="available_from" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Available From <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="date" 
                                name="available_from" 
                                id="available_from" 
                                value="{{ old('available_from', $room->available_from ? \Carbon\Carbon::parse($room->available_from)->format('Y-m-d') : '') }}"
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600"
                                required>
                            @error('available_from')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="mb-6">
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select 
                                name="status" 
                                id="status"
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600"
                                required>
                                <option value="available" {{ old('status', $room->status) === 'available' ? 'selected' : '' }}>Available</option>
                                <option value="booked" {{ old('status', $room->status) === 'booked' ? 'selected' : '' }}>Booked</option>
                                <option value="inactive" {{ old('status', $room->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="flex items-center space-x-4">
                            <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-md transition">
                                Update Room
                            </button>
                            <a href="{{ route('owner.rooms.index') }}" class="px-6 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-medium rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
