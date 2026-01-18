<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Online Room Rental System</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            <!-- Navigation -->
            <nav class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <div class="flex-shrink-0 flex items-center">
                                <a href="{{ route('home') }}" class="text-xl font-bold text-gray-800 dark:text-gray-200">
                                    Online Room Rental System
                                </a>
                            </div>
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                <a href="{{ route('home') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-900 dark:text-gray-100">
                                    Home
                                </a>
                                <a href="{{ route('rooms.index') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                                    Browse Rooms
                                </a>
                            </div>
                        </div>
                        
                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            @auth
                                <a href="{{ route('dashboard') }}" class="text-sm text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100 mr-4">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="text-sm text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100 mr-4">
                                    Login
                                </a>
                                <a href="{{ route('register') }}" class="text-sm bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                                    Register
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Hero Section -->
            <div class="bg-white dark:bg-gray-800 py-16">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h1 class="text-4xl font-bold text-gray-900 dark:text-gray-100 mb-4">
                        Find Your Perfect Room
                    </h1>
                    <p class="text-xl text-gray-600 dark:text-gray-400 mb-8">
                        Connect with room owners and find affordable rental options across Nepal
                    </p>
                    <a href="{{ route('rooms.index') }}" class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-semibold px-8 py-3 rounded-lg">
                        Browse Available Rooms
                    </a>
                </div>
            </div>

            <!-- Latest Rooms -->
            <div class="py-12">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">Latest Available Rooms</h2>
                    
                    @if ($latestRooms->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($latestRooms as $room)
                                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                                    <div class="p-6">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                                            {{ $room->title }}
                                        </h3>
                                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">
                                            {{ Str::limit($room->description, 100) }}
                                        </p>
                                        <div class="flex justify-between items-center mb-4">
                                            <span class="text-gray-600 dark:text-gray-400 text-sm">
                                                📍 {{ $room->city }}, {{ $room->province }}
                                            </span>
                                            <span class="text-blue-600 font-semibold">
                                                NPR {{ number_format($room->rent_price) }}/mo
                                            </span>
                                        </div>
                                        <a href="{{ route('rooms.show', $room) }}" class="block w-full text-center bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-medium px-4 py-2 rounded">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-600 dark:text-gray-400">No rooms available at the moment.</p>
                    @endif
                </div>
            </div>

            <!-- Footer -->
            <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 py-8">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-gray-600 dark:text-gray-400">
                    <p>&copy; {{ date('Y') }} Online Room Rental System. All rights reserved.</p>
                </div>
            </footer>
        </div>
    </body>
</html>
