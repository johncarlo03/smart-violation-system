

<aside class="w-64 bg-white border-r border-gray-100 shadow-sm hidden md:flex flex-col shrink-0">
            <a href="{{ route('profile.edit') }}"
                class="px-6 py-5 border-b border-gray-100 flex items-center justify-between hover:bg-gray-50/70 transition duration-200 group">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="relative shrink-0">
                        @if(Auth::user()->profile_photo)
                            <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}"
                                 class="w-10 h-10 rounded-xl object-cover shadow-sm group-hover:scale-105 transition duration-200">
                        @else
                            <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white font-black text-sm shadow-sm group-hover:scale-105 transition duration-200">
                                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                            </div>
                        @endif
                    </div>
                    <div class="truncate min-w-0">
                        <h1 class="text-sm font-black text-gray-800 tracking-tight truncate group-hover:text-indigo-600 transition duration-200">
                            {{ Auth::user()->name }}
                        </h1>
                        <p class="text-[10px] font-black uppercase tracking-wider text-gray-400 mt-0.5 flex items-center gap-1">
                             Super Admin
                        </p>
                    </div>
                </div>
                <div class="text-gray-300 group-hover:text-indigo-500 group-hover:translate-x-0.5 transition duration-200 hidden sm:block pl-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </a>

            <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
                <x-sidebar-link
                route="superadmin.dashboard"
                active="superadmin.dashboard*">
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" />
                    </svg>
                    {{ __('Infrastructure Panel') }}
                </x-sidebar-link>
                

                <x-sidebar-link
                route="superadmin.users"
                active="superadmin.users*">
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    {{ __('User Directory Governance') }}
                </x-sidebar-link>

                <a href="#"
                    class="flex items-center gap-3 px-4 py-3 text-gray-500 hover:text-gray-800 hover:bg-gray-50 rounded-xl font-bold text-sm transition duration-200 group">
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                    </svg>
                    {{ __('System Triggers') }}
                </a>
            </nav>

            <div class="p-4 border-t border-gray-100 bg-gray-50/50 flex flex-col gap-3">
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-red-50 hover:bg-red-100 border border-red-100 text-red-600 rounded-xl text-xs font-black uppercase tracking-wider transition duration-200 shadow-sm">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </aside>