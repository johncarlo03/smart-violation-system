<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 flex">

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
                {{-- Inactive Link Style --}}
                <a href="{{ route('superadmin.dashboard') }}"
                    class="flex items-center gap-3 px-4 py-3 text-gray-500 hover:text-gray-800 hover:bg-gray-50 rounded-xl font-bold text-sm transition duration-200 group">
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" />
                    </svg>
                    {{ __('Infrastructure Panel') }}
                </a>

                {{-- Highlighted Active Link Style --}}
                <a href="#"
                    class="flex items-center gap-3 px-4 py-3 bg-indigo-50/60 border border-indigo-100/50 rounded-xl text-indigo-600 font-bold text-sm transition duration-200">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    {{ __('User Directory Governance') }}
                </a>

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

        {{-- Main Content Canvas Window --}}
        <div class="flex-1 flex flex-col min-w-0 overflow-y-auto max-h-screen">
            <main class="p-8 lg:p-10">

                {{-- Header Titles Section --}}
                <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h2 class="text-3xl font-black text-gray-800 tracking-tight">
                            User Directory Governance
                        </h2>
                        <p class="text-sm text-gray-500 mt-1">
                            Provision security credentials, override clear access roles, and register personnel layers.
                        </p>
                    </div>
                </div>

                {{-- Status Alert Banner Component --}}
                @if (session('success'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-100 rounded-2xl flex items-center gap-3 shadow-sm">
                        <div class="w-8 h-8 rounded-xl bg-green-100 flex items-center justify-center text-green-600 shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <p class="text-sm font-semibold text-green-800">{{ session('success') }}</p>
                    </div>
                @endif

                {{-- Core Provisioning Card Module --}}
                <div class="w-full  bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-gray-100/50">
                        <h3 class="text-lg font-black text-gray-800">
                            User Form
                        </h3>
                    </div>

                    <form action="#" method="POST" class="p-6 space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Full Name Input Field --}}
                            <div>
                                <label class="block text-xs font-black uppercase tracking-[0.15em] text-gray-400 mb-2">
                                    Full Name
                                </label>
                                <input type="text" name="name" value="{{ old('name') }}" required
                                    class="w-full rounded-xl border-gray-200 text-sm font-medium text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-200">
                                @error('name')
                                    <p class="text-xs font-bold text-red-500 mt-1.5 pl-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-black uppercase tracking-[0.15em] text-gray-400 mb-2">
                                    ID Number:
                                </label>
                                <input type="number" name="id_number" value="{{ old('id_number') }}" required
                                    class="w-full rounded-xl border-gray-200 text-sm font-medium text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-200">
                                @error('id_number')
                                    <p class="text-xs font-bold text-red-500 mt-1.5 pl-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-black uppercase tracking-[0.15em] text-gray-400 mb-2">
                                    rfid(if applicable)
                                </label>
                                <input type="number" name="id_number" value="{{ old('id_number') }}" required
                                    class="w-full rounded-xl border-gray-200 text-sm font-medium text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-200">
                                @error('id_number')
                                    <p class="text-xs font-bold text-red-500 mt-1.5 pl-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Institutional Email Address Input Field --}}
                            <div>
                                <label class="block text-xs font-black uppercase tracking-[0.15em] text-gray-400 mb-2">
                                    Email
                                </label>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                    class="w-full rounded-xl border-gray-200 text-sm font-medium text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-200">
                                @error('email')
                                    <p class="text-xs font-bold text-red-500 mt-1.5 pl-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Account Security Access Password Input --}}
                            <div>
                                <label class="block text-xs font-black uppercase tracking-[0.15em] text-gray-400 mb-2">
                                    Password
                                </label>
                                <input type="password" name="password" required
                                    class="w-full rounded-xl border-gray-200 text-sm font-medium text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-200">
                                @error('password')
                                    <p class="text-xs font-bold text-red-500 mt-1.5 pl-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Confirm Password Input Field --}}
                            <div>
                                <label class="block text-xs font-black uppercase tracking-[0.15em] text-gray-400 mb-2">
                                    Confirm Password
                                </label>
                                <input type="password" name="password_confirmation" required
                                    class="w-full rounded-xl border-gray-200 text-sm font-medium text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-200">
                            </div>
                        </div>

                        <hr class="border-gray-100">

                        {{-- Structural Role Assignment Radio Nodes --}}
                        <div>
                            <label class="block text-xs font-black uppercase tracking-[0.15em] text-gray-400 mb-3">
                                Structural Clearance Layer Assignment
                            </label>
                            
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                                <label class="relative flex items-start p-4 rounded-xl border border-gray-200 hover:bg-gray-50 cursor-pointer transition duration-200">
                                    <div class="flex items-center h-5">
                                        <input type="radio" name="role" value="1" {{ old('role') == '1' ? 'checked' : '' }} class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                    </div>
                                    <div class="ml-3">
                                        <span class="block text-xs font-black uppercase tracking-wider text-blue-600">Student</span>
                                        <span class="block text-[11px] font-medium text-gray-400 mt-0.5">Logs incidents & data</span>
                                    </div>
                                </label>

                                <label class="relative flex items-start p-4 rounded-xl border border-gray-200 hover:bg-gray-50 cursor-pointer transition duration-200">
                                    <div class="flex items-center h-5">
                                        <input type="radio" name="role" value="2" {{ old('role') == '2' ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                    </div>
                                    <div class="ml-3">
                                        <span class="block text-xs font-black uppercase tracking-wider text-orange-600">CSO Officer</span>
                                        <span class="block text-[11px] font-medium text-gray-400 mt-0.5">Logs incidents & data</span>
                                    </div>
                                </label>

                                {{-- SAO Option --}}
                                <label class="relative flex items-start p-4 rounded-xl border border-gray-200 hover:bg-gray-50 cursor-pointer transition duration-200">
                                    <div class="flex items-center h-5">
                                        <input type="radio" name="role" value="3" {{ old('role') == '3' ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                    </div>
                                    <div class="ml-3">
                                        <span class="block text-xs font-black uppercase tracking-wider text-green-600">SAO Faculty</span>
                                        <span class="block text-[11px] font-medium text-gray-400 mt-0.5">Manages system scopes</span>
                                    </div>
                                </label>

                                {{-- Super Admin Option --}}
                                <label class="relative flex items-start p-4 rounded-xl border border-gray-200 hover:bg-gray-50 cursor-pointer transition duration-200">
                                    <div class="flex items-center h-5">
                                        <input type="radio" name="role" value="4" {{ old('role') == '4' ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                    </div>
                                    <div class="ml-3">
                                        <span class="block text-xs font-black uppercase tracking-wider text-indigo-600">Super Admin</span>
                                        <span class="block text-[11px] font-medium text-gray-400 mt-0.5">Complete root control</span>
                                    </div>
                                </label>
                            </div>
                            @error('role')
                                <p class="text-xs font-bold text-red-500 mt-2 pl-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Interface Commit Form Submission Panel --}}
                        <div class="pt-4 border-t border-gray-100 flex justify-end">
                            <button type="submit"
                                class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white rounded-xl text-xs font-black uppercase tracking-widest transition duration-200 shadow-md hover:shadow-lg active:scale-[0.98]">
                                Register User
                            </button>
                        </div>
                    </form>
                </div>

            </main>
        </div>
    </div>
</x-app-layout>