@php
    $saUser = Auth::user();
    $saName = trim($saUser->name ?? 'SA');
    $saParts = preg_split('/\s+/', $saName);
    $saInitials = strtoupper(
        substr($saParts[0] ?? 'S', 0, 1) .
        (count($saParts) > 1 ? substr(end($saParts), 0, 1) : substr($saParts[0] ?? 'A', 1, 1))
    );
@endphp

<div x-data="{ mobileNavOpen: false }" class="md:flex md:shrink-0">
    {{-- Mobile Top Bar (Visible below md) --}}
    <div class="md:hidden bg-white border-b border-gray-200 px-4 py-3 flex items-center justify-between gap-3 sticky top-0 z-40">
        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 min-w-0">
            @if($saUser->profile_photo)
                <img src="{{ asset('storage/' . $saUser->profile_photo) }}"
                     alt="{{ $saUser->name }}"
                     onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');"
                     class="w-9 h-9 rounded-full object-cover border border-amber-500 shrink-0">
                <div class="hidden w-9 h-9 rounded-full bg-[#1f2937] text-amber-400 border border-amber-500 flex items-center justify-center font-black text-xs shrink-0">
                    {{ $saInitials }}
                </div>
            @else
                <div class="w-9 h-9 rounded-full bg-[#1f2937] text-amber-400 border border-amber-500 flex items-center justify-center font-black text-xs shrink-0">
                    {{ $saInitials }}
                </div>
            @endif

            <div class="min-w-0">
                <p class="text-sm font-black text-gray-900 truncate">{{ $saUser->name }}</p>
                <p class="text-[10px] font-black uppercase tracking-wider text-amber-600">Super Admin</p>
            </div>
        </a>

        <button type="button"
                @click="mobileNavOpen = !mobileNavOpen"
                class="inline-flex items-center justify-center p-2 rounded-xl text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none transition shrink-0"
                aria-label="Toggle navigation menu">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path x-show="!mobileNavOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 6h16M4 12h16M4 18h16" />
                <path x-show="mobileNavOpen" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    {{-- Mobile Off-Canvas Drawer (Visible below md) --}}
    <div class="md:hidden">
        <div x-show="mobileNavOpen"
             x-cloak
             x-transition:enter="transition-opacity ease-linear duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-900/50 z-50"
             @click="mobileNavOpen = false">
        </div>

        <aside x-show="mobileNavOpen"
               x-cloak
               x-transition:enter="transition ease-in-out duration-250 transform"
               x-transition:enter-start="-translate-x-full"
               x-transition:enter-end="translate-x-0"
               x-transition:leave="transition ease-in-out duration-250 transform"
               x-transition:leave-start="translate-x-0"
               x-transition:leave-end="-translate-x-full"
               class="fixed inset-y-0 left-0 w-72 max-w-[85vw] bg-white shadow-2xl z-[60] flex flex-col">

            <div class="p-4 bg-[#1f2937] text-white flex items-center justify-between gap-2 border-b-2 border-amber-500 shrink-0">
                <a href="{{ route('profile.edit') }}" @click="mobileNavOpen = false" class="flex items-center gap-3 min-w-0">
                    @if($saUser->profile_photo)
                        <img src="{{ asset('storage/' . $saUser->profile_photo) }}"
                             alt="{{ $saUser->name }}"
                             onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');"
                             class="w-10 h-10 rounded-full object-cover border border-amber-400 shrink-0">
                        <div class="hidden w-10 h-10 rounded-full bg-gray-800 text-amber-400 border border-amber-400 flex items-center justify-center font-black text-xs shrink-0">
                            {{ $saInitials }}
                        </div>
                    @else
                        <div class="w-10 h-10 rounded-full bg-gray-800 text-amber-400 border border-amber-400 flex items-center justify-center font-black text-xs shrink-0">
                            {{ $saInitials }}
                        </div>
                    @endif

                    <div class="min-w-0">
                        <p class="text-sm font-black text-white truncate">{{ $saUser->name }}</p>
                        <p class="text-[10px] font-black uppercase tracking-wider text-amber-400 mt-0.5">Super Admin</p>
                    </div>
                </a>

                <button type="button"
                        @click="mobileNavOpen = false"
                        class="p-1.5 rounded-lg text-gray-300 hover:text-white hover:bg-gray-700 transition shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <nav class="flex-1 px-4 py-5 space-y-1.5 overflow-y-auto">
                <x-sidebar-link route="superadmin.dashboard" active="superadmin.dashboard*">
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" />
                    </svg>
                    <span class="truncate">{{ __('Infrastructure Panel') }}</span>
                </x-sidebar-link>

                <x-sidebar-link route="superadmin.users.index" active="superadmin.users.index*">
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    <span class="truncate">{{ __('User Directory Governance') }}</span>
                </x-sidebar-link>

                <x-sidebar-link route="superadmin.offenses.index" active="superadmin.offenses.index*">
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m0-6v2m0-6a9 9 0 11-18 0 9 9 0 0118 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <span class="truncate">{{ __('Offenses') }}</span>
                </x-sidebar-link>

                <x-sidebar-link route="superadmin.audits.index" active="superadmin.audits.index*">
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m0-6v2m0-6a9 9 0 11-18 0 9 9 0 0118 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <span class="truncate">{{ __('Audit') }}</span>
                </x-sidebar-link>
            </nav>

            <div class="p-4 border-t border-gray-100 bg-gray-50/50 shrink-0">
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-red-50 hover:bg-red-100 border border-red-200 text-red-700 rounded-xl text-xs font-black uppercase tracking-wider transition duration-200">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </aside>
    </div>

    {{-- Desktop Sidebar (Visible md and up) --}}
    <aside class="w-64 md:h-screen md:sticky md:top-0 bg-white border-r border-gray-100 shadow-sm hidden md:flex flex-col shrink-0">
        <!-- Profile Link -->
        <a href="{{ route('profile.edit') }}"
            class="px-6 py-5 border-b border-gray-100 flex items-center justify-between hover:bg-gray-50/70 transition duration-200 group shrink-0">
            <div class="flex items-center gap-3 min-w-0">
                @if($saUser->profile_photo)
                    <img src="{{ asset('storage/' . $saUser->profile_photo) }}"
                         alt="{{ $saUser->name }}"
                         onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');"
                         class="w-10 h-10 rounded-full object-cover border border-amber-500 shadow-sm group-hover:scale-105 transition duration-200 shrink-0">
                    <div class="hidden w-10 h-10 rounded-full bg-[#1f2937] text-amber-400 border border-amber-500 flex items-center justify-center font-black text-xs shadow-sm group-hover:scale-105 transition duration-200 shrink-0">
                        {{ $saInitials }}
                    </div>
                @else
                    <div class="w-10 h-10 rounded-full bg-[#1f2937] text-amber-400 border border-amber-500 flex items-center justify-center font-black text-xs shadow-sm group-hover:scale-105 transition duration-200 shrink-0">
                        {{ $saInitials }}
                    </div>
                @endif
            </div>
            <div class="truncate min-w-0">
                <h1 class="text-sm font-black text-gray-800 tracking-tight truncate group-hover:text-gray-950 transition duration-200">
                    {{ $saUser->name }}
                </h1>
                <p class="text-[10px] font-black uppercase tracking-wider text-amber-600 mt-0.5 flex items-center gap-1">
                    Super Admin
                </p>
            </div>
            <div class="text-gray-300 group-hover:text-gray-600 group-hover:translate-x-0.5 transition duration-200 hidden sm:block pl-2 shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                </svg>
            </div>
        </a>

        <!-- Navigation Links -->
        <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
            <x-sidebar-link route="superadmin.dashboard" active="superadmin.dashboard*">
                <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" />
                </svg>
                <span class="truncate">{{ __('Infrastructure Panel') }}</span>
            </x-sidebar-link>

            <x-sidebar-link route="superadmin.users.index" active="superadmin.users.index*">
                <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
                <span class="truncate">{{ __('User Directory Governance') }}</span>
            </x-sidebar-link>

            <x-sidebar-link route="superadmin.offenses.index" active="superadmin.offenses.index*">
                <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 15v2m0-6v2m0-6a9 9 0 11-18 0 9 9 0 0118 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <span class="truncate">{{ __('Offenses') }}</span>
            </x-sidebar-link>

            <x-sidebar-link
                route="superadmin.audits.index"
                active="superadmin.audits.index*">
                <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 15v2m0-6v2m0-6a9 9 0 11-18 0 9 9 0 0118 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <span class="truncate">{{ __('Audit') }}</span>
            </x-sidebar-link>
        </nav>

        <!-- Bottom Section -->
        <div class="p-4 border-t border-gray-100 bg-gray-50/50 shrink-0">
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit"
                    class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-red-50 hover:bg-red-100 border border-red-200 text-red-700 rounded-xl text-xs font-black uppercase tracking-wider transition duration-200 shadow-xs">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
    </aside>
</div>