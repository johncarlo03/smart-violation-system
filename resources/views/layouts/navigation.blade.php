@php
    $navUser = Auth::user();
    $navPhotoUrl = $navUser->profile_photo
        ? asset('storage/' . $navUser->profile_photo)
        : 'https://ui-avatars.com/api/?name=' . urlencode($navUser->name) . '&background=1f2937&color=fbbf24&bold=true';
@endphp

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

                    @if(in_array($navUser->role, [3, 6, 7]))
                        <x-nav-link :href="route('sao.violations.index')" :active="request()->routeIs('sao.violations.index')">
                            {{ __('Violation Records') }}
                        </x-nav-link>
                    @endif

                    @if(in_array($navUser->role, [2, 5]))
                        <x-nav-link :href="route('teacher.reports.index')" :active="request()->routeIs('teacher.reports.index')">
                            {{ __('My Reports') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown (Desktop) -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-2.5 px-3 py-1.5 border border-transparent text-sm leading-4 font-medium rounded-lg text-gray-700 bg-white hover:text-gray-900 hover:bg-gray-50 focus:outline-none transition ease-in-out duration-150">
                            <img src="{{ $navPhotoUrl }}"
                                 alt="{{ $navUser->name }}"
                                 class="w-7 h-7 rounded-full object-cover border border-amber-500 shrink-0 bg-gray-100">

                            <span>{{ $navUser->name }}</span>

                            <div>
                                <svg class="fill-current h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
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
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-lg text-gray-500 hover:text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-800 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Left-Side Off-Canvas Drawer -->
    <div class="sm:hidden">
        <!-- Backdrop Overlay -->
        <div x-show="open"
             x-transition:enter="transition-opacity ease-linear duration-250"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-900/50 z-50"
             @click="open = false"
             style="display: none;">
        </div>

        <!-- Sliding Drawer Panel from Left -->
        <div x-show="open"
             x-transition:enter="transition ease-in-out duration-300 transform"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in-out duration-250 transform"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full"
             class="fixed inset-y-0 left-0 max-w-xs w-full bg-white shadow-2xl z-[60] flex flex-col"
             style="display: none;"
             @click.outside="open = false">

            <!-- Drawer Header / User Info with User Profile Photo -->
            <div class="p-5 bg-[#1f2937] text-white flex items-center justify-between gap-2 border-b-2 border-amber-500">
                <div class="flex items-center gap-3 min-w-0">
                    <img src="{{ $navPhotoUrl }}"
                         alt="{{ $navUser->name }}"
                         class="w-10 h-10 rounded-full object-cover border-2 border-amber-400 shrink-0 bg-gray-700">

                    <div class="overflow-hidden">
                        <h3 class="font-bold text-base truncate">{{ $navUser->name }}</h3>
                        <p class="text-xs text-gray-300 truncate">{{ $navUser->email }}</p>
                    </div>
                </div>

                <button @click="open = false" class="p-1.5 rounded-lg hover:bg-gray-700 text-gray-300 hover:text-white transition shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Drawer Navigation Links -->
            <div class="flex-1 overflow-y-auto px-4 py-6 space-y-1.5 bg-white">
                <a href="{{ route('dashboard') }}"
                   class="block px-4 py-2.5 rounded-lg text-sm font-semibold transition {{ request()->routeIs('*dashboard') ? 'bg-gray-100 text-gray-900 border-l-4 border-red-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    {{ __('Dashboard') }}
                </a>

                @if(in_array($navUser->role, [3, 6, 7]))
                    <a href="{{ route('sao.violations.index') }}"
                       class="block px-4 py-2.5 rounded-lg text-sm font-semibold transition {{ request()->routeIs('sao.violations.index') ? 'bg-gray-100 text-gray-900 border-l-4 border-red-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        {{ __('Violation Records') }}
                    </a>
                @endif

                @if(in_array($navUser->role, [2, 5]))
                    <a href="{{ route('teacher.reports.index') }}"
                       class="block px-4 py-2.5 rounded-lg text-sm font-semibold transition {{ request()->routeIs('teacher.reports.index') ? 'bg-gray-100 text-gray-900 border-l-4 border-red-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        {{ __('My Reports') }}
                    </a>
                @endif

                <a href="{{ route('profile.edit') }}"
                   class="block px-4 py-2.5 rounded-lg text-sm font-semibold transition {{ request()->routeIs('profile.edit') ? 'bg-gray-100 text-gray-900 border-l-4 border-red-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    {{ __('Profile Settings') }}
                </a>
            </div>

            <!-- Drawer Footer / Logout -->
            <div class="p-4 border-t border-gray-100 bg-gray-50">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2.5 rounded-lg text-sm font-semibold text-red-700 hover:bg-red-50 transition">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>