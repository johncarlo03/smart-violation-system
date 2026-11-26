<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3 min-w-0">
            <h2 class="font-black text-lg sm:text-xl text-gray-900 tracking-tight truncate">
                {{ __('Profile') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6 sm:py-10 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            {{-- Profile Information --}}
            <div class="bg-white rounded-2xl shadow-xs border border-gray-200/80 overflow-hidden">
                <div class="h-1 w-full bg-gradient-to-r from-[#1f2937] to-amber-500"></div>
                <div class="p-4 sm:p-6 lg:p-8 min-w-0">
                    <div class="max-w-xl min-w-0">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div>

            {{-- Update Password --}}
            <div class="bg-white rounded-2xl shadow-xs border border-gray-200/80 overflow-hidden">
                <div class="h-1 w-full bg-gradient-to-r from-amber-500 to-[#1f2937]"></div>
                <div class="p-4 sm:p-6 lg:p-8 min-w-0">
                    <div class="max-w-xl min-w-0">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>

            {{-- Delete Account --}}
            <div class="bg-white rounded-2xl shadow-xs border border-red-200/80 overflow-hidden">
                <div class="h-1 w-full bg-gradient-to-r from-red-700 to-amber-500"></div>
                <div class="p-4 sm:p-6 lg:p-8 min-w-0">
                    <div class="max-w-xl min-w-0">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>