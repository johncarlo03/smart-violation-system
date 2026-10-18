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
    <body class="font-sans antialiased text-gray-800 bg-gray-50 min-h-screen flex items-center justify-center bg-cover bg-center bg-no-repeat relative px-4 py-10" style="background-image: url('{{ asset('images/campus.jpg') }}');">
        
        <!-- Dark Overlay -->
        <div class="absolute inset-0 bg-gray-900/70 backdrop-blur-sm"></div>

        <!-- Register Card -->
        <div class="relative z-10 w-full max-w-md bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl p-8 sm:p-10 border border-white/40">
            
            <!-- Logo & Header -->
            <div class="text-center mb-6">
                <img src="{{ asset('images/ctu-logo.png') }}" alt="CTU Logo" class="h-16 w-16 mx-auto mb-3 drop-shadow-md">
                <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">Create an Account</h2>
                <p class="text-sm text-gray-500 mt-1">Join the CTU Smart Violation System</p>
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

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Full Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-red-600 focus:border-red-600 transition bg-gray-50 focus:bg-white text-gray-900 placeholder-gray-400"
                        placeholder="John Doe">
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email Address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-red-600 focus:border-red-600 transition bg-gray-50 focus:bg-white text-gray-900 placeholder-gray-400"
                        placeholder="student@ctu.edu.ph">
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-red-600 focus:border-red-600 transition bg-gray-50 focus:bg-white text-gray-900 placeholder-gray-400"
                        placeholder="••••••••">
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1">Confirm Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-red-600 focus:border-red-600 transition bg-gray-50 focus:bg-white text-gray-900 placeholder-gray-400"
                        placeholder="••••••••">
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-3.5 px-4 bg-red-700 hover:bg-red-800 text-white font-bold rounded-xl shadow-lg hover:shadow-red-500/30 transition-all transform hover:-translate-y-0.5 mt-2">
                    Register
                </button>
            </form>

            <!-- Login Link -->
            <div class="mt-6 text-center text-sm text-gray-600">
                Already registered? 
                <a href="{{ route('login') }}" class="font-bold text-red-700 hover:text-red-900 transition">Log in here</a>
            </div>

            <!-- Back to Home Link -->
            <div class="mt-4 text-center">
                <a href="/" class="text-xs text-gray-400 hover:text-gray-600 transition">&larr; Back to Home</a>
            </div>
        </div>
    </body>
</html>