<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CTU Smart Violation - Portal</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        .bg-grid-pattern {
            background-image: radial-gradient(#e5e7eb 1px, transparent 1px);
            background-size: 20px 20px;
        }
        .gpu-accelerated {
            will-change: transform;
            transform: translateZ(0);
        }

        /* Mobile-only responsiveness: desktop styles remain unchanged */
        @media (max-width: 767px) {
            body {
                align-items: flex-start;
                justify-content: center;
                min-height: 100vh;
                min-height: 100svh;
                padding: 1.25rem 0.75rem;
            }

            .auth-container {
                width: 100%;
                max-width: 28rem;
                height: auto;
                min-height: 0;
                flex-direction: column;
                border-radius: 1.5rem;
            }

            .mobile-auth-toggle {
                display: flex;
                width: 100%;
                gap: 0.5rem;
                padding: 1rem 1.25rem 0;
                background: white;
            }

            .mobile-auth-toggle button {
                flex: 1;
                min-width: 0;
                padding: 0.7rem 0.75rem;
                border: 1px solid #e5e7eb;
                border-radius: 0.75rem;
                color: #6b7280;
                background: #f9fafb;
                font-size: 0.875rem;
                font-weight: 700;
                transition: background-color 0.2s, color 0.2s, border-color 0.2s;
            }

            .mobile-auth-toggle button.active {
                color: #ffffff;
                border-color: #b91c1c;
                background: #b91c1c;
            }

            .auth-panel {
                width: 100%;
                height: auto;
                min-height: 0;
                padding: 1.5rem 1.25rem 1.75rem;
            }

            .auth-panel.opacity-0 {
                display: none;
            }

            .auth-panel.opacity-100 {
                display: flex;
            }

            .auth-panel>div {
                width: 100%;
                height: auto;
                max-width: 24rem;
            }

            .auth-panel h2 {
                font-size: 1.75rem;
                line-height: 2.1rem;
            }

            .sliding-auth-overlay {
                display: none;
            }
        }

        @media (max-width: 380px) {
            body {
                padding-right: 0.5rem;
                padding-left: 0.5rem;
            }

            .auth-panel {
                padding-right: 1rem;
                padding-left: 1rem;
            }

            .mobile-auth-toggle {
                padding-right: 1rem;
                padding-left: 1rem;
            }

            .auth-panel h2 {
                font-size: 1.6rem;
            }
        }

        @media (min-width: 768px) {
            .mobile-auth-toggle {
                display: none;
            }
        }
    </style>
</head>

<body
    class="font-sans antialiased text-gray-800 bg-gray-900 min-h-screen flex items-center justify-center bg-cover bg-center bg-no-repeat relative px-4 py-8 sm:py-12"
    style="background-image: url('{{ asset('images/campus.jpg') }}');">

    <!-- Dark Overlay -->
    <div class="absolute inset-0 bg-gray-900/90"></div>

<<<<<<< HEAD
    <!-- Main Auth Container: Stacked full-width on mobile, side-by-side flex on sm+ screens -->
    <div class="relative z-10 w-full max-w-4xl bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col sm:flex-row sm:h-[680px]"
        x-data="{ isRegister: {{ $errors->any() && old('name') ? 'true' : 'false' }} }">

        <!-- MOBILE SWITCHER HEADER (Visible only on small screens) -->
        <div class="sm:hidden bg-gradient-to-r from-red-600 to-red-900 p-6 text-white text-center shrink-0">
            <div class="flex justify-center mb-3">
                <div class="p-2.5 bg-white/10 rounded-full">
                    <img src="{{ asset('images/ctu-logo.png') }}" alt="CTU Logo" class="h-14 w-14">
                </div>
            </div>
            <h1 class="text-xl font-extrabold tracking-tight">CTU Smart Violation Portal</h1>
            <p class="text-red-100 text-xs mt-1">Manage and track campus records securely.</p>
            
            <!-- Tab Switcher for Mobile -->
            <div class="flex bg-black/20 p-1 rounded-xl mt-4">
                <button type="button" @click="isRegister = false"
                    :class="!isRegister ? 'bg-white text-red-700 shadow-sm font-bold' : 'text-white hover:text-red-100 font-medium'"
                    class="flex-1 py-2 text-xs rounded-lg transition-all">
                    Sign In
                </button>
                <button type="button" @click="isRegister = true"
                    :class="isRegister ? 'bg-white text-red-700 shadow-sm font-bold' : 'text-white hover:text-red-100 font-medium'"
                    class="flex-1 py-2 text-xs rounded-lg transition-all">
                    Register
                </button>
            </div>
        </div>
=======
    <!-- Main Auth Container -->
    <div class="auth-container relative z-10 w-full max-w-5xl h-[650px] bg-white rounded-[2rem] shadow-2xl overflow-hidden flex"
        x-data="{ isRegister: false }">


        <!-- MOBILE-ONLY AUTH TOGGLE -->
        <div class="mobile-auth-toggle" aria-label="Choose sign-in or registration">
            <button type="button" :class="!isRegister ? 'active' : ''" @click="isRegister = false">
                Sign In
            </button>
            <button type="button" :class="isRegister ? 'active' : ''" @click="isRegister = true">
                Create Account
            </button>
        </div>

        <!-- LEFT SIDE: Register Form -->
        <div class="auth-panel w-1/2 h-full flex flex-col items-center justify-center p-10 bg-grid-pattern transition-opacity duration-300 z-0"
            :class="isRegister ? 'opacity-100 pointer-events-auto' : 'opacity-0 pointer-events-none'">
>>>>>>> 1d59d1b5ce0a7e0780d4220a0d526b9b63e4c881

        <!-- LEFT SIDE: Register Form (Desktop Side / Mobile Stacked) -->
        <div class="w-full sm:w-1/2 flex flex-col justify-between p-6 sm:p-10 bg-grid-pattern overflow-y-auto max-h-[75vh] sm:max-h-none"
            x-show="isRegister"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100">

            <div class="w-full max-w-sm mx-auto my-auto py-2">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mb-1">Create Account</h2>
                <p class="text-gray-500 text-xs sm:text-sm mb-5">Join the CTU Smart Violation System today.</p>

                <!-- Validation Errors -->
                @if ($errors->any() && old('name'))
                    <div class="mb-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-xs p-3 rounded-r-lg">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="space-y-3">
                    @csrf

                    <!-- Name Input -->
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400 group-focus-within:text-red-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <input id="register_name" type="text" name="name" value="{{ old('name') }}" required
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-red-600 focus:border-red-600 transition-all bg-white shadow-sm text-xs sm:text-sm"
                            placeholder="Full Name">
                    </div>

                    <!-- ID Number -->
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400 group-focus-within:text-red-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                            </svg>
                        </div>
                        <input id="register_id_number" type="text" name="id_number" value="{{ old('id_number') }}" required
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-red-600 focus:border-red-600 transition-all bg-white shadow-sm text-xs sm:text-sm"
                            placeholder="Student ID Number">
                    </div>

                    <!-- Department & Course Dropdowns Component -->
                    <div class="space-y-3" x-data="{
                        dept: '{{ old('department_id') }}',
                        course: '{{ old('course_id') }}',
                        courses: @js($courses),
                    }">
                        <select name="department_id" x-model="dept" @change="course = ''" required
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-red-600 focus:border-red-600 transition-all bg-white shadow-sm text-xs sm:text-sm text-gray-700">
                            <option value="">Select Department</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>

                        <select name="course_id" x-model="course" :disabled="!dept" required
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-red-600 focus:border-red-600 transition-all bg-white shadow-sm text-xs sm:text-sm text-gray-700 disabled:bg-gray-100 disabled:text-gray-400">
                            <option value="" x-text="dept ? 'Select Course' : 'Select Department First'"></option>
                            <template x-for="c in (courses[dept] || [])" :key="c.id">
                                <option :value="c.id" x-text="c.name" :selected="String(c.id) === String(course)"></option>
                            </template>
                        </select>

                        <select name="year_level" required
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-red-600 focus:border-red-600 transition-all bg-white shadow-sm text-xs sm:text-sm text-gray-700">
                            <option value="">Select Year Level</option>
                            @foreach([1 => '1st Year', 2 => '2nd Year', 3 => '3rd Year', 4 => '4th Year'] as $value => $label)
                                <option value="{{ $value }}" {{ old('year_level') == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Email Input -->
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400 group-focus-within:text-red-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                            </svg>
                        </div>
                        <input id="register_email" type="email" name="email" value="{{ old('email') }}" required
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-red-600 focus:border-red-600 transition-all bg-white shadow-sm text-xs sm:text-sm"
                            placeholder="Email Address">
                    </div>

                    <!-- Password Input -->
<<<<<<< HEAD
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400 group-focus-within:text-red-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <input id="register_password" type="password" name="password" required
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-red-600 focus:border-red-600 transition-all bg-white shadow-sm text-xs sm:text-sm"
=======

                    <!-- Password Input -->
                    <div class="relative group" x-data="{ showPassword: false }">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-red-600 transition-colors"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                </path>
                            </svg>
                        </div>

                        <input id="register_password" :type="showPassword ? 'text' : 'password'" name="password"
                            required
                            class="w-full pl-11 pr-12 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-red-600 focus:border-red-600 transition-all bg-white shadow-sm text-sm"
>>>>>>> 1d59d1b5ce0a7e0780d4220a0d526b9b63e4c881
                            placeholder="Password">

                        <button type="button" @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-red-600 transition-colors"
                            :aria-label="showPassword ? 'Hide password' : 'Show password'">

                            <!-- Eye Icon -->
                            <svg x-show="!showPassword" class="h-5 w-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                </path>
                            </svg>

                            <!-- Eye Off Icon -->
                            <svg x-show="showPassword" x-cloak class="h-5 w-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3l18 18M10.584 10.587a2 2 0 002.828 2.828 M9.88 5.09A10.94 10.94 0 0112 4.875c4.478 0 8.268 2.943 9.542 7a10.97 10.97 0 01-4.132 5.411 M6.228 6.228A10.97 10.97 0 002.458 12c1.274 4.057 5.064 7 9.542 7 1.61 0 3.13-.398 4.464-1.102">
                                </path>
                            </svg>
                        </button>
                    </div>

                    <!-- Confirm Password Input -->
<<<<<<< HEAD
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400 group-focus-within:text-red-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <input id="register_password_confirmation" type="password" name="password_confirmation" required
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-red-600 focus:border-red-600 transition-all bg-white shadow-sm text-xs sm:text-sm"
=======

                    <!-- Confirm Password Input -->
                    <div class="relative group" x-data="{ showPassword: false }">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-red-600 transition-colors"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                </path>
                            </svg>
                        </div>

                        <input id="register_password_confirmation" :type="showPassword ? 'text' : 'password'"
                            name="password_confirmation" required
                            class="w-full pl-11 pr-12 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-red-600 focus:border-red-600 transition-all bg-white shadow-sm text-sm"
>>>>>>> 1d59d1b5ce0a7e0780d4220a0d526b9b63e4c881
                            placeholder="Confirm Password">

                        <button type="button" @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-red-600 transition-colors"
                            :aria-label="showPassword ? 'Hide password' : 'Show password'">

                            <svg x-show="!showPassword" class="h-5 w-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                </path>
                            </svg>

                            <svg x-show="showPassword" x-cloak class="h-5 w-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3l18 18M10.584 10.587a2 2 0 002.828 2.828 M9.88 5.09A10.94 10.94 0 0112 4.875c4.478 0 8.268 2.943 9.542 7a10.97 10.97 0 01-4.132 5.411 M6.228 6.228A10.97 10.97 0 002.458 12c1.274 4.057 5.064 7 9.542 7 1.61 0 3.13-.398 4.464-1.102">
                                </path>
                            </svg>
                        </button>
                    </div>


                    <button type="submit"
                        class="w-full py-2.5 sm:py-3 mt-1 bg-gradient-to-r from-red-600 to-red-800 hover:from-red-700 hover:to-red-900 text-white font-bold rounded-xl shadow transition-all text-xs sm:text-sm">
                        Create Account
                    </button>
                </form>

                <div class="mt-4 text-center">
                    <a href="/" class="inline-flex items-center gap-1.5 text-xs sm:text-sm text-gray-500 hover:text-red-700 transition-colors font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Home
                    </a>
                </div>
            </div>
        </div>

<<<<<<< HEAD
        <!-- RIGHT SIDE: Login Form (Desktop Side / Mobile Stacked) -->
        <div class="w-full sm:w-1/2 flex flex-col justify-between p-6 sm:p-10 bg-grid-pattern overflow-y-auto max-h-[75vh] sm:max-h-none"
            x-show="!isRegister"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100">
=======
        <!-- RIGHT SIDE: Login Form -->
        <div class="auth-panel w-1/2 h-full flex flex-col items-center justify-center p-10 bg-grid-pattern transition-opacity duration-300 z-0"
            :class="isRegister ? 'opacity-0 pointer-events-none' : 'opacity-100 pointer-events-auto'">
>>>>>>> 1d59d1b5ce0a7e0780d4220a0d526b9b63e4c881

            <div class="w-full max-w-sm mx-auto my-auto py-2">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mb-1">Welcome Back</h2>
                <p class="text-gray-500 text-xs sm:text-sm mb-6">Please enter your details to sign in.</p>

                <!-- Validation Errors -->
                @if ($errors->any() && !old('name'))
                    <div class="mb-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-xs p-3 rounded-r-lg">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <!-- Email Input -->
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400 group-focus-within:text-red-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                            </svg>
                        </div>
                        <input id="login_email" type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-red-600 focus:border-red-600 transition-all bg-white shadow-sm text-xs sm:text-sm"
                            placeholder="Email Address">
                    </div>

                    <!-- Password Input -->
<<<<<<< HEAD
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400 group-focus-within:text-red-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <input id="login_password" type="password" name="password" required
                            class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-red-600 focus:border-red-600 transition-all bg-white shadow-sm text-xs sm:text-sm"
=======

                    <!-- Password Input -->
                    <div class="relative group" x-data="{ showPassword: false }">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-red-600 transition-colors"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                </path>
                            </svg>
                        </div>

                        <input id="login_password" :type="showPassword ? 'text' : 'password'" name="password" required
                            class="w-full pl-11 pr-12 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-red-600 focus:border-red-600 transition-all bg-white shadow-sm text-sm"
>>>>>>> 1d59d1b5ce0a7e0780d4220a0d526b9b63e4c881
                            placeholder="Password">

                        <button type="button" @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-red-600 transition-colors"
                            :aria-label="showPassword ? 'Hide password' : 'Show password'">

                            <svg x-show="!showPassword" class="h-5 w-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                </path>
                            </svg>

                            <svg x-show="showPassword" x-cloak class="h-5 w-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3l18 18M10.584 10.587a2 2 0 002.828 2.828 M9.88 5.09A10.94 10.94 0 0112 4.875c4.478 0 8.268 2.943 9.542 7a10.97 10.97 0 01-4.132 5.411 M6.228 6.228A10.97 10.97 0 002.458 12c1.274 4.057 5.064 7 9.542 7 1.61 0 3.13-.398 4.464-1.102">
                                </path>
                            </svg>
                        </button>
                    </div>

<<<<<<< HEAD
                    <div class="flex items-center justify-between text-xs sm:text-sm mt-2">
                        <label class="flex items-center text-gray-600 cursor-pointer">
                            <input type="checkbox" name="remember" class="mr-2 rounded border-gray-300 text-red-600 focus:ring-red-500 w-4 h-4"> Remember me
=======

                    <div class="flex items-center justify-between mt-2">
                        <label class="flex items-center text-sm text-gray-600 cursor-pointer">
                            <input type="checkbox" name="remember"
                                class="mr-2 rounded border-gray-300 text-red-600 focus:ring-red-500 w-4 h-4"> Remember
                            me
>>>>>>> 1d59d1b5ce0a7e0780d4220a0d526b9b63e4c881
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="font-semibold text-red-600 hover:text-red-800 transition">Forgot password?</a>
                        @endif
                    </div>

                    <button type="submit"
                        class="w-full py-3 mt-2 bg-gradient-to-r from-red-600 to-red-800 hover:from-red-700 hover:to-red-900 text-white font-bold rounded-xl shadow transition-all text-xs sm:text-sm">
                        Sign In
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <a href="/" class="inline-flex items-center gap-1.5 text-xs sm:text-sm text-gray-500 hover:text-red-700 transition-colors font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Home
                    </a>
                </div>
            </div>
        </div>

<<<<<<< HEAD
        <!-- SLIDING OVERLAY PANEL: Hidden on mobile (`hidden sm:flex`), active on desktop (`sm:flex`) -->
        <div class="absolute top-0 left-0 w-1/2 h-full bg-gradient-to-br from-red-600 to-red-900 text-white hidden sm:flex flex-col items-center justify-center p-10 transition-transform duration-500 ease-in-out z-20 shadow-xl gpu-accelerated"
=======
        <!-- SLIDING OVERLAY PANEL: Optimized Transitions & Removed heavy blur filters -->
        <div class="sliding-auth-overlay absolute top-0 left-0 w-1/2 h-full bg-gradient-to-br from-red-600 to-red-900 text-white flex flex-col items-center justify-center p-10 transition-transform duration-500 ease-in-out z-20 shadow-xl overflow-gpu-accelerated"
>>>>>>> 1d59d1b5ce0a7e0780d4220a0d526b9b63e4c881
            :class="isRegister ? 'translate-x-full' : 'translate-x-0'">

            <!-- Background shapes -->
            <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none opacity-20">
                <div class="absolute -top-20 -left-20 w-64 h-64 bg-white rounded-full"></div>
                <div class="absolute bottom-10 -right-10 w-48 h-48 bg-yellow-400 rounded-full"></div>
            </div>

            <div class="relative z-10 flex flex-col items-center text-center">
                <div class="p-4 bg-white/10 rounded-full mb-6 shadow-inner">
                    <img src="{{ asset('images/ctu-logo.png') }}" alt="CTU Logo" class="h-24 w-24">
                </div>

                <!-- Text for Login Mode -->
                <div x-show="!isRegister" class="space-y-6 w-full max-w-[280px]">
                    <h2 class="text-3xl font-extrabold tracking-tight">New Here?</h2>
                    <p class="text-red-100 text-sm leading-relaxed">Create an account to start reporting and tracking violations seamlessly.</p>
                    <button @click="isRegister = true"
                        class="w-full py-3 px-8 border-2 border-white/50 rounded-xl font-bold hover:bg-white hover:text-red-700 hover:border-white transition-all">
                        Create Account
                    </button>
                </div>

                <!-- Text for Register Mode -->
                <div x-show="isRegister" class="space-y-6 w-full max-w-[280px]" style="display: none;">
                    <h2 class="text-3xl font-extrabold tracking-tight">Welcome Back!</h2>
                    <p class="text-red-100 text-sm leading-relaxed">Already part of the system? Log in to access your dashboard and records.</p>
                    <button @click="isRegister = false"
                        class="w-full py-3 px-8 border-2 border-white/50 rounded-xl font-bold hover:bg-white hover:text-red-700 hover:border-white transition-all">
                        Sign In
                    </button>
                </div>
            </div>
        </div>

    </div>
</body>

</html>