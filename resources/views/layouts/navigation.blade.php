<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 relative z-40">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('*dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @if(in_array(Auth::user()->role, [3]))
                        <x-nav-link :href="route('sao.violations.index')" :active="request()->routeIs('sao.violations.index')">
                            {{ __('Violation Records') }}
                        </x-nav-link>
                    @endif

                    @if(in_array(Auth::user()->role, [2, 5]))
                        <x-nav-link :href="route('teacher.reports.index')" :active="request()->routeIs('teacher.reports.index')">
                            {{ __('My Reports') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Left-Side Off-Canvas Drawer (Floating overlay, won't squeeze dashboard) -->
    <div class="sm:hidden">
        <!-- Backdrop Overlay -->
        <div x-show="open" 
             x-transition:enter="transition-opacity ease-linear duration-300" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100" 
             x-transition:leave="transition-opacity ease-linear duration-300" 
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black/50 z-50" 
             @click="open = false" 
             style="display: none;">
        </div>

        <!-- Sliding Drawer Panel from Left -->
        <div x-show="open" 
             x-transition:enter="transition ease-in-out duration-300 transform" 
             x-transition:enter-start="-translate-x-full" 
             x-transition:enter-end="translate-x-0" 
             x-transition:leave="transition ease-in-out duration-300 transform" 
             x-transition:leave-start="translate-x-0" 
             x-transition:leave-end="-translate-x-full"
             class="fixed inset-y-0 left-0 max-w-xs w-full bg-white shadow-2xl z-[60] flex flex-col"
             style="display: none;"
             @click.outside="open = false">

            <!-- Drawer Header / User Info -->
            <div class="p-5 bg-orange-800 text-white flex items-center justify-between">
                <div class="overflow-hidden pr-2">
                    <h3 class="font-bold text-base truncate">{{ Auth::user()->name }}</h3>
                    <p class="text-xs text-orange-200 truncate">{{ Auth::user()->email }}</p>
                </div>
                <button @click="open = false" class="p-1.5 rounded-full hover:bg-orange-700/60 text-orange-200 hover:text-white transition shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Drawer Navigation Links -->
            <div class="flex-1 overflow-y-auto px-4 py-6 space-y-2">
                <a href="{{ route('dashboard') }}" 
                   class="block px-4 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('*dashboard') ? 'bg-orange-50 text-orange-800' : 'text-gray-600 hover:bg-gray-50' }}">
                    {{ __('Dashboard') }}
                </a>

                @if(in_array(Auth::user()->role, [3]))
                    <a href="{{ route('sao.violations.index') }}" 
                       class="block px-4 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('sao.violations.index') ? 'bg-orange-50 text-orange-800' : 'text-gray-600 hover:bg-gray-50' }}">
                        {{ __('Violation Records') }}
                    </a>
                @endif

                @if(in_array(Auth::user()->role, [2, 5]))
                    <a href="{{ route('teacher.reports.index') }}" 
                       class="block px-4 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('teacher.reports.index') ? 'bg-orange-50 text-orange-800' : 'text-gray-600 hover:bg-gray-50' }}">
                        {{ __('My Reports') }}
                    </a>
                @endif

                <a href="{{ route('profile.edit') }}" 
                   class="block px-4 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('profile.edit') ? 'bg-orange-50 text-orange-800' : 'text-gray-600 hover:bg-gray-50' }}">
                    {{ __('Profile Settings') }}
                </a>
            </div>

            <!-- Drawer Footer / Logout -->
            <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2.5 rounded-xl text-sm font-semibold text-rose-600 hover:bg-rose-50 transition">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>