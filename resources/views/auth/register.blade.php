<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Register - CTU Smart Violation</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="font-sans antialiased text-gray-800 bg-gray-900 min-h-screen flex items-center justify-center bg-cover bg-center bg-no-repeat relative px-4 py-10" style="background-image: url('{{ asset('images/campus.jpg') }}');">
        
        <!-- Optimized Dark Overlay (Replaced backdrop-filter with solid opacity) -->
        <div class="absolute inset-0 bg-gray-900/90"></div>

        <!-- Register Card -->
        <div class="relative z-10 w-full max-w-md bg-white rounded-3xl shadow-2xl p-8 sm:p-10 border border-gray-100">
            
            <!-- Header -->
            <div class="text-center mb-8">
                <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Create Account</h2>
                <p class="text-sm text-gray-500 mt-1.5">Join the CTU Smart Violation System today.</p>
            </div>

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-600 text-sm p-3 rounded-lg">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <!-- Full Name -->
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400 group-focus-within:text-red-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                        class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-red-600 focus:border-red-600 transition-all bg-white shadow-sm text-sm text-gray-900 placeholder-gray-400"
                        placeholder="Full Name">
                </div>

                <!-- Student ID Number -->
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400 group-focus-within:text-red-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                        </svg>
                    </div>
                    <input id="register_id_number" type="text" name="id_number" value="{{ old('id_number') }}" required
                        class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-red-600 focus:border-red-600 transition-all bg-white shadow-sm text-sm text-gray-900 placeholder-gray-400"
                        placeholder="Student ID Number">
                </div>

                <!-- Email Address -->
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400 group-focus-within:text-red-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                        </svg>
                    </div>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                        class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-red-600 focus:border-red-600 transition-all bg-white shadow-sm text-sm text-gray-900 placeholder-gray-400"
                        placeholder="Email Address">
                </div>

                <!-- Password -->
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400 group-focus-within:text-red-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <input id="password" type="password" name="password" required autocomplete="new-password"
                        class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-red-600 focus:border-red-600 transition-all bg-white shadow-sm text-sm text-gray-900 placeholder-gray-400"
                        placeholder="Password">
                </div>

                <!-- Confirm Password -->
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400 group-focus-within:text-red-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                        class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-red-600 focus:border-red-600 transition-all bg-white shadow-sm text-sm text-gray-900 placeholder-gray-400"
                        placeholder="Confirm Password">
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-3.5 px-4 bg-gradient-to-r from-red-600 to-red-800 hover:from-red-700 hover:to-red-900 text-white font-bold rounded-xl shadow transition-all mt-3">
                    Create Account
                </button>
            </form>

            <!-- Back to Home Link -->
            <div class="mt-6 text-center">
                <a href="/" class="text-sm font-medium text-gray-500 hover:text-gray-800 transition flex items-center justify-center gap-1">
                    &larr; Back to Home
                </a>
            </div>
        </div>
    </body>
</html>