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
            /* A subtle grid pattern for the white background */
            .bg-grid-pattern {
                background-image: radial-gradient(#e5e7eb 1px, transparent 1px);
                background-size: 20px 20px;
            }
        </style>
    </head>
    <body class="font-sans antialiased text-gray-800 bg-gray-900 min-h-screen flex items-center justify-center bg-cover bg-center bg-no-repeat relative px-4 py-8" style="background-image: url('{{ asset('images/campus.jpg') }}');">
        
        <!-- Dark Overlay -->
        <div class="absolute inset-0 bg-gray-900/80 backdrop-blur-sm"></div>

        <!-- Main Auth Container -->
        <div class="relative z-10 w-full max-w-5xl h-[650px] bg-white rounded-[2rem] shadow-[0_20px_50px_rgba(0,0,0,0.5)] overflow-hidden flex" x-data="{ isRegister: false }">
            
            <!-- LEFT SIDE: Register Form (Hidden initially) -->
            <div class="w-1/2 h-full flex flex-col items-center justify-center p-10 bg-grid-pattern transition-opacity duration-500 z-0"
                 :class="isRegister ? 'opacity-100 pointer-events-auto' : 'opacity-0 pointer-events-none'">
                
                <div class="w-full max-w-sm flex flex-col h-full justify-center">
                    <h2 class="text-3xl font-extrabold text-gray-900 mb-2">Create Account</h2>
                    <p class="text-gray-500 text-sm mb-6">Join the CTU Smart Violation System today.</p>

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

                    <form method="POST" action="{{ route('register') }}" class="space-y-3.5">
                        @csrf
                        
                        <!-- Name Input -->
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-red-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <input id="register_name" type="text" name="name" value="{{ old('name') }}" required
                                class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-red-600 focus:border-red-600 transition-all bg-white shadow-sm text-sm" placeholder="Full Name">
                        </div>

                         <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-red-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                            </div>
                            <input id="register_id_number" type="text" name="id_number" value="{{ old('id_number') }}" required
                                class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-red-600 focus:border-red-600 transition-all bg-white shadow-sm text-sm" placeholder="Student ID Number">
                        </div>
                        
                        <!-- Email Input -->
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-red-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                            </div>
                            <input id="register_email" type="email" name="email" value="{{ old('email') }}" required
                                class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-red-600 focus:border-red-600 transition-all bg-white shadow-sm text-sm" placeholder="Email Address">
                        </div>

                        <!-- Password Input -->
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-red-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </div>
                            <input id="register_password" type="password" name="password" required
                                class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-red-600 focus:border-red-600 transition-all bg-white shadow-sm text-sm" placeholder="Password">
                        </div>

                        <!-- Confirm Password Input -->
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-red-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            </div>
                            <input id="register_password_confirmation" type="password" name="password_confirmation" required
                                class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-red-600 focus:border-red-600 transition-all bg-white shadow-sm text-sm" placeholder="Confirm Password">
                        </div>

                        <button type="submit" class="w-full py-3 mt-2 bg-gradient-to-r from-red-600 to-red-800 hover:from-red-700 hover:to-red-900 text-white font-bold rounded-xl shadow-lg hover:shadow-red-500/30 transition-all transform hover:-translate-y-0.5">
                            Create Account
                        </button>
                    </form>

                    <!-- Back to Home Link (Register) -->
                    <div class="mt-6 text-center">
                        <a href="/" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-red-700 transition-colors font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Back to Home
                        </a>
                    </div>
                </div>
            </div>

            <!-- RIGHT SIDE: Login Form (Visible initially) -->
            <div class="w-1/2 h-full flex flex-col items-center justify-center p-10 bg-grid-pattern transition-opacity duration-500 z-0"
                 :class="isRegister ? 'opacity-0 pointer-events-none' : 'opacity-100 pointer-events-auto'">
                
                <div class="w-full max-w-sm flex flex-col h-full justify-center">
                    <h2 class="text-3xl font-extrabold text-gray-900 mb-2">Welcome Back</h2>
                    <p class="text-gray-500 text-sm mb-8">Please enter your details to sign in.</p>

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

                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf
                        
                        <!-- Email Input -->
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-red-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                            </div>
                            <input id="login_email" type="email" name="email" value="{{ old('email') }}" required autofocus
                                class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-red-600 focus:border-red-600 transition-all bg-white shadow-sm text-sm" placeholder="Email Address">
                        </div>

                        <!-- Password Input -->
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-red-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </div>
                            <input id="login_password" type="password" name="password" required
                                class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-red-600 focus:border-red-600 transition-all bg-white shadow-sm text-sm" placeholder="Password">
                        </div>
                        
                        <div class="flex items-center justify-between mt-2">
                            <label class="flex items-center text-sm text-gray-600 cursor-pointer">
                                <input type="checkbox" name="remember" class="mr-2 rounded border-gray-300 text-red-600 focus:ring-red-500 w-4 h-4"> Remember me
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-sm font-semibold text-red-600 hover:text-red-800 transition">Forgot password?</a>
                            @endif
                        </div>

                        <button type="submit" class="w-full py-3.5 mt-2 bg-gradient-to-r from-red-600 to-red-800 hover:from-red-700 hover:to-red-900 text-white font-bold rounded-xl shadow-lg hover:shadow-red-500/30 transition-all transform hover:-translate-y-0.5">
                            Sign In
                        </button>
                    </form>

                    <!-- Back to Home Link (Login) -->
                    <div class="mt-8 text-center">
                        <a href="/" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-red-700 transition-colors font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Back to Home
                        </a>
                    </div>
                </div>
            </div>

            <!-- SLIDING OVERLAY PANEL -->
            <div class="absolute top-0 left-0 w-1/2 h-full bg-gradient-to-br from-red-600 via-red-700 to-red-900 text-white flex flex-col items-center justify-center p-10 transition-transform duration-700 cubic-bezier(0.68, -0.55, 0.265, 1.55) z-20 shadow-[0_0_40px_rgba(0,0,0,0.3)] overflow-hidden"
                 :class="isRegister ? 'translate-x-full' : 'translate-x-0'">
                
                <!-- Decorative Background Circles -->
                <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
                    <div class="absolute -top-20 -left-20 w-64 h-64 bg-white opacity-10 rounded-full blur-2xl"></div>
                    <div class="absolute bottom-10 -right-10 w-48 h-48 bg-yellow-400 opacity-20 rounded-full blur-2xl"></div>
                    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 border-[1px] border-white/10 rounded-full"></div>
                    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-72 h-72 border-[1px] border-white/10 rounded-full"></div>
                </div>

                <div class="relative z-10 flex flex-col items-center text-center">
                    <div class="p-4 bg-white/10 rounded-full backdrop-blur-md mb-6 shadow-inner">
                        <img src="{{ asset('images/ctu-logo.png') }}" alt="CTU Logo" class="h-24 w-24 drop-shadow-2xl">
                    </div>

                    <!-- Text for Login Mode -->
                    <div x-show="!isRegister" class="space-y-6 w-full max-w-[280px]">
                        <h2 class="text-3xl font-extrabold tracking-tight">New Here?</h2>
                        <p class="text-red-100 text-sm leading-relaxed">Create an account to start reporting and tracking violations seamlessly.</p>
                        <button @click="isRegister = true" class="w-full py-3 px-8 border-2 border-white/50 rounded-xl font-bold hover:bg-white hover:text-red-700 hover:border-white transition-all backdrop-blur-sm">
                            Create Account
                        </button>
                    </div>

                    <!-- Text for Register Mode -->
                    <div x-show="isRegister" class="space-y-6 w-full max-w-[280px]" style="display: none;">
                        <h2 class="text-3xl font-extrabold tracking-tight">Welcome Back!</h2>
                        <p class="text-red-100 text-sm leading-relaxed">Already part of the system? Log in to access your dashboard and records.</p>
                        <button @click="isRegister = false" class="w-full py-3 px-8 border-2 border-white/50 rounded-xl font-bold hover:bg-white hover:text-red-700 hover:border-white transition-all backdrop-blur-sm">
                            Sign In
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </body>
</html>