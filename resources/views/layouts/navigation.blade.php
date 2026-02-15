<nav x-data="{ open: false }" class="fixed top-0 left-0 right-0 z-50 bg-white/95 dark:bg-gray-900/95 backdrop-blur-sm border-b border-gray-200 dark:border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <span class="text-2xl font-black text-primary-600 tracking-tighter">RoomRent</span>
            </a>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-primary-600 dark:text-primary-400 font-semibold' : 'text-gray-600 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-500 transition font-medium' }}">Home</a>
                <a href="{{ route('rooms.index') }}" class="{{ request()->routeIs('rooms.*') ? 'text-primary-600 dark:text-primary-400 font-semibold' : 'text-gray-600 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-500 transition font-medium' }}">Browse Rooms</a>

                @auth
                    @if(auth()->user()->role === 'renter')
                        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'text-primary-600 dark:text-primary-400 font-semibold' : 'text-gray-600 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-500 transition font-medium' }}">Dashboard</a>
                        <a href="{{ route('my-bookings') }}" class="{{ request()->routeIs('my-bookings') ? 'text-primary-600 dark:text-primary-400 font-semibold' : 'text-gray-600 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-500 transition font-medium' }}">My Bookings</a>
                    @elseif(auth()->user()->role === 'owner')
                        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'text-primary-600 dark:text-primary-400 font-semibold' : 'text-gray-600 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-500 transition font-medium' }}">Dashboard</a>
                        <a href="{{ route('owner.rooms.index') }}" class="{{ request()->routeIs('owner.rooms.*') ? 'text-primary-600 dark:text-primary-400 font-semibold' : 'text-gray-600 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-500 transition font-medium' }}">My Rooms</a>
                        <a href="{{ route('my-bookings') }}" class="{{ request()->routeIs('my-bookings') ? 'text-primary-600 dark:text-primary-400 font-semibold' : 'text-gray-600 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-500 transition font-medium' }}">My Bookings</a>
                    @elseif(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'text-primary-600 dark:text-primary-400 font-semibold' : 'text-gray-600 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-500 transition font-medium' }}">Dashboard</a>
                        <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'text-primary-600 dark:text-primary-400 font-semibold' : 'text-gray-600 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-500 transition font-medium' }}">Users</a>
                        <a href="{{ route('admin.rooms.index') }}" class="{{ request()->routeIs('admin.rooms.*') ? 'text-primary-600 dark:text-primary-400 font-semibold' : 'text-gray-600 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-500 transition font-medium' }}">Rooms</a>
                        <a href="{{ route('admin.bookings.index') }}" class="{{ request()->routeIs('admin.bookings.*') ? 'text-primary-600 dark:text-primary-400 font-semibold' : 'text-gray-600 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-500 transition font-medium' }}">Bookings</a>
                        <a href="{{ route('admin.settings.index') }}" class="{{ request()->routeIs('admin.settings.*') ? 'text-primary-600 dark:text-primary-400 font-semibold' : 'text-gray-600 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-500 transition font-medium' }}">Settings</a>
                    @endif

                    <!-- User Dropdown -->
                    <div x-data="{ dropdownOpen: false }" class="relative">
                        <button @click="dropdownOpen = !dropdownOpen" class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-500 transition rounded-lg">
                            <div class="w-8 h-8 bg-primary-100 dark:bg-primary-900/30 rounded-full flex items-center justify-center">
                                <span class="text-primary-600 dark:text-primary-400 font-bold text-xs">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                            </div>
                            <span>{{ Auth::user()->name }}</span>
                            <x-role-badge :role="Auth::user()->role" />
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>

                        <div x-show="dropdownOpen" @click.away="dropdownOpen = false" x-transition class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 py-2 z-50">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">Profile</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">Log Out</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-gray-600 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-500 transition font-medium">Login</a>
                    <a href="{{ route('register') }}" class="px-5 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition font-medium shadow-sm hover:shadow-md">List Your Room</a>
                @endauth
            </div>

            <!-- Mobile Menu Button -->
            <button @click="open = !open" class="md:hidden text-gray-700 dark:text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path :class="{'hidden': open, 'inline-flex': !open}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    <path :class="{'hidden': !open, 'inline-flex': open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div :class="{'block': open, 'hidden': !open}" class="hidden md:hidden bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800">
        <div class="px-4 py-3 space-y-2">
            <a href="{{ route('home') }}" class="block py-2 {{ request()->routeIs('home') ? 'text-primary-600 dark:text-primary-400 font-semibold' : 'text-gray-600 dark:text-gray-300 hover:text-primary-600 font-medium' }}">Home</a>
            <a href="{{ route('rooms.index') }}" class="block py-2 {{ request()->routeIs('rooms.*') ? 'text-primary-600 dark:text-primary-400 font-semibold' : 'text-gray-600 dark:text-gray-300 hover:text-primary-600 font-medium' }}">Browse Rooms</a>

            @auth
                @if(auth()->user()->role === 'renter')
                    <a href="{{ route('dashboard') }}" class="block py-2 {{ request()->routeIs('dashboard') ? 'text-primary-600 dark:text-primary-400 font-semibold' : 'text-gray-600 dark:text-gray-300 hover:text-primary-600 font-medium' }}">Dashboard</a>
                    <a href="{{ route('my-bookings') }}" class="block py-2 {{ request()->routeIs('my-bookings') ? 'text-primary-600 dark:text-primary-400 font-semibold' : 'text-gray-600 dark:text-gray-300 hover:text-primary-600 font-medium' }}">My Bookings</a>
                @elseif(auth()->user()->role === 'owner')
                    <a href="{{ route('dashboard') }}" class="block py-2 {{ request()->routeIs('dashboard') ? 'text-primary-600 dark:text-primary-400 font-semibold' : 'text-gray-600 dark:text-gray-300 hover:text-primary-600 font-medium' }}">Dashboard</a>
                    <a href="{{ route('owner.rooms.index') }}" class="block py-2 {{ request()->routeIs('owner.rooms.*') ? 'text-primary-600 dark:text-primary-400 font-semibold' : 'text-gray-600 dark:text-gray-300 hover:text-primary-600 font-medium' }}">My Rooms</a>
                    <a href="{{ route('my-bookings') }}" class="block py-2 {{ request()->routeIs('my-bookings') ? 'text-primary-600 dark:text-primary-400 font-semibold' : 'text-gray-600 dark:text-gray-300 hover:text-primary-600 font-medium' }}">My Bookings</a>
                @elseif(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="block py-2 {{ request()->routeIs('admin.dashboard') ? 'text-primary-600 dark:text-primary-400 font-semibold' : 'text-gray-600 dark:text-gray-300 hover:text-primary-600 font-medium' }}">Dashboard</a>
                    <a href="{{ route('admin.users.index') }}" class="block py-2 {{ request()->routeIs('admin.users.*') ? 'text-primary-600 dark:text-primary-400 font-semibold' : 'text-gray-600 dark:text-gray-300 hover:text-primary-600 font-medium' }}">Users</a>
                    <a href="{{ route('admin.rooms.index') }}" class="block py-2 {{ request()->routeIs('admin.rooms.*') ? 'text-primary-600 dark:text-primary-400 font-semibold' : 'text-gray-600 dark:text-gray-300 hover:text-primary-600 font-medium' }}">Rooms</a>
                    <a href="{{ route('admin.bookings.index') }}" class="block py-2 {{ request()->routeIs('admin.bookings.*') ? 'text-primary-600 dark:text-primary-400 font-semibold' : 'text-gray-600 dark:text-gray-300 hover:text-primary-600 font-medium' }}">Bookings</a>
                    <a href="{{ route('admin.settings.index') }}" class="block py-2 {{ request()->routeIs('admin.settings.*') ? 'text-primary-600 dark:text-primary-400 font-semibold' : 'text-gray-600 dark:text-gray-300 hover:text-primary-600 font-medium' }}">Settings</a>
                @endif

                <div class="border-t border-gray-200 dark:border-gray-700 pt-3 mt-3">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-8 h-8 bg-primary-100 dark:bg-primary-900/30 rounded-full flex items-center justify-center">
                            <span class="text-primary-600 dark:text-primary-400 font-bold text-xs">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                        </div>
                        <div>
                            <div class="font-medium text-sm text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                            <div class="text-xs text-gray-500">{{ Auth::user()->email }}</div>
                            <div class="mt-1"><x-role-badge :role="Auth::user()->role" /></div>
                        </div>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="block py-2 text-gray-600 dark:text-gray-300 hover:text-primary-600 font-medium">Profile</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left py-2 text-gray-600 dark:text-gray-300 hover:text-primary-600 font-medium">Log Out</button>
                    </form>
                </div>
            @else
                <div class="border-t border-gray-200 dark:border-gray-700 pt-3 mt-3">
                    <a href="{{ route('login') }}" class="block py-2 text-gray-600 dark:text-gray-300 hover:text-primary-600 font-medium">Login</a>
                    <a href="{{ route('register') }}" class="block py-2 text-primary-600 dark:text-primary-500 font-semibold">Sign Up</a>
                </div>
            @endauth
        </div>
    </div>
</nav>
