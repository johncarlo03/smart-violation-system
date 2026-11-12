<section>
    <header>
        <h2 class="text-lg font-bold text-gray-900 tracking-tight">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-xs sm:text-sm text-gray-500">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Current Password')" class="text-xs sm:text-sm font-semibold text-gray-700" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1.5 block w-full rounded-xl border-gray-300 text-xs sm:text-sm focus:border-orange-500 focus:ring-orange-500 transition shadow-sm" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('New Password')" class="text-xs sm:text-sm font-semibold text-gray-700" />
            <x-text-input id="update_password_password" name="password" type="password" class="mt-1.5 block w-full rounded-xl border-gray-300 text-xs sm:text-sm focus:border-orange-500 focus:ring-orange-500 transition shadow-sm" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" class="text-xs sm:text-sm font-semibold text-gray-700" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1.5 block w-full rounded-xl border-gray-300 text-xs sm:text-sm focus:border-orange-500 focus:ring-orange-500 transition shadow-sm" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit" 
                class="px-5 py-2.5 rounded-xl bg-orange-800 hover:bg-orange-900 text-white text-xs sm:text-sm font-semibold transition focus:outline-none active:scale-[0.99] shadow-sm">
                {{ __('Save Password') }}
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2500)"
                    class="text-xs font-semibold text-emerald-600 flex items-center gap-1"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ __('Password updated successfully.') }}
                </p>
            @endif
        </div>
    </form>
</section>