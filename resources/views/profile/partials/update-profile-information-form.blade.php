<section>
    <header>
        <h2 class="text-lg font-bold text-gray-900 tracking-tight">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-xs sm:text-sm text-gray-500">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-5" enctype="multipart/form-data">
        @csrf
        @method('patch')

        {{-- Profile Photo Selection with Instant Image Preview --}}
        <div x-data="{ photoPreview: null }">
            <x-input-label for="profile_photo" :value="__('Profile Photo')" class="text-xs sm:text-sm font-semibold text-gray-700" />
            
            <div class="flex flex-col sm:flex-row sm:items-center gap-4 mt-2">
                {{-- Current Avatar --}}
                <div class="shrink-0" x-show="!photoPreview">
                    <img class="w-20 h-20 rounded-2xl object-cover border-2 border-gray-100 shadow-sm bg-gray-50"
                        src="{{ $user->profile_photo ? asset('storage/' . $user->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}"
                        alt="{{ $user->name }}">
                </div>

                {{-- Live Image Preview --}}
                <div class="shrink-0" x-show="photoPreview" style="display: none;">
                    <span class="block w-20 h-20 rounded-2xl bg-cover bg-no-repeat bg-center border-2 border-orange-500 shadow-sm"
                          :style="'background-image: url(\'' + photoPreview + '\');'">
                    </span>
                </div>

                {{-- File Input Control --}}
                <div class="flex-1">
                    <input type="file" name="profile_photo" id="profile_photo"
                        x-ref="photo"
                        @change="
                            const reader = new FileReader();
                            reader.onload = (e) => { photoPreview = e.target.result; };
                            reader.readAsDataURL($refs.photo.files[0]);
                        "
                        accept=".jpg,.jpeg,.png"
                        class="block w-full text-xs sm:text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-orange-50 file:text-orange-800 hover:file:bg-orange-100 transition cursor-pointer" />
                    <p class="text-[11px] text-gray-400 mt-1">Recommended square format (JPG or PNG, max 2MB).</p>
                </div>
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('profile_photo')" />
        </div>

        {{-- Name Input --}}
        <div>
            <x-input-label for="name" :value="__('Full Name')" class="text-xs sm:text-sm font-semibold text-gray-700" />
            <x-text-input id="name" name="name" type="text" class="mt-1.5 block w-full rounded-xl border-gray-300 text-xs sm:text-sm focus:border-orange-500 focus:ring-orange-500 transition shadow-sm" 
                :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        {{-- Email Input --}}
        <div>
            <x-input-label for="email" :value="__('Email Address')" class="text-xs sm:text-sm font-semibold text-gray-700" />
            <x-text-input id="email" name="email" type="email" class="mt-1.5 block w-full rounded-xl border-gray-300 text-xs sm:text-sm focus:border-orange-500 focus:ring-orange-500 transition shadow-sm" 
                :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div class="mt-3 bg-amber-50 border border-amber-200/80 rounded-xl p-3">
                    <p class="text-xs text-amber-900">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification"
                            class="underline font-semibold text-amber-900 hover:text-amber-950 focus:outline-none ml-1">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-semibold text-xs text-emerald-700">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        {{-- Save Button & Status --}}
        <div class="flex items-center gap-3 pt-2">
            <button type="submit" 
                class="px-5 py-2.5 rounded-xl bg-orange-800 hover:bg-orange-900 text-white text-xs sm:text-sm font-semibold transition focus:outline-none active:scale-[0.99] shadow-sm">
                {{ __('Save Changes') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2500)"
                    class="text-xs font-semibold text-emerald-600 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ __('Saved successfully.') }}
                </p>
            @endif
        </div>
    </form>
</section>