<section class="space-y-5">
    <header>
        <h2 class="text-lg font-bold text-rose-900 tracking-tight">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-xs sm:text-sm text-gray-500 leading-relaxed">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    {{-- Disabled state with visual hint badge --}}
    <div class="flex flex-col sm:flex-row sm:items-center gap-3 pt-2">
        <x-danger-button disabled class="opacity-50 cursor-not-allowed w-full sm:w-auto justify-center rounded-xl py-2.5 px-4 text-xs sm:text-sm font-semibold shadow-none"
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        >
            {{ __('Delete Account') }}
        </x-danger-button>

        <span class="inline-flex items-center gap-1.5 text-xs text-rose-600 font-medium bg-rose-50 px-3 py-1.5 rounded-xl border border-rose-100 self-start sm:self-auto">
            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0 0v2m0-2h2m-2 0H10m11 3.5V6a2 2 0 00-2-2H5a2 2 0 00-2 2v14.5A1.5 1.5 0 004.5 22h15a1.5 1.5 0 001.5-1.5z"/>
            </svg>
            {{ __('Account deletion is currently disabled.') }}
        </span>
    </div>

    {{-- Confirmation Modal --}}
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-5 sm:p-6 space-y-4">
            @csrf
            @method('delete')

            <div>
                <h2 class="text-base sm:text-lg font-bold text-gray-900">
                    {{ __('Are you sure you want to delete your account?') }}
                </h2>

                <p class="mt-1.5 text-xs sm:text-sm text-gray-500 leading-relaxed">
                    {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                </p>
            </div>

            <div>
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-full rounded-xl border-gray-300 text-xs sm:text-sm focus:border-rose-500 focus:ring-rose-500 transition shadow-sm"
                    placeholder="{{ __('Enter your password to confirm') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="pt-2 flex flex-col-reverse sm:flex-row sm:justify-end gap-2 sm:gap-3">
                <x-secondary-button x-on:click="$dispatch('close')" class="w-full sm:w-auto justify-center rounded-xl py-2 px-4 text-xs sm:text-sm">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="w-full sm:w-auto justify-center rounded-xl py-2 px-4 text-xs sm:text-sm bg-rose-700 hover:bg-rose-800 focus:ring-rose-500 shadow-sm">
                    {{ __('Delete Account') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>