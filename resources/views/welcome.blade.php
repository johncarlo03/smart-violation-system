<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>CTU Smart Violation System</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="bg-gray-50 text-gray-800 font-sans antialiased min-h-screen flex flex-col relative overflow-x-hidden">
        
        <!-- Header Navigation -->
        <header class="w-full bg-white/90 backdrop-blur-md shadow-sm absolute top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
                <!-- Logo & Title -->
                <div class="flex items-center gap-4 cursor-pointer" onclick="window.location.href='/'">
                    <img src="{{ asset('images/ctu-logo.png') }}" alt="CTU Logo" class="h-14 w-14 object-contain drop-shadow-md transition-transform hover:scale-105" onerror="this.src='https://upload.wikimedia.org/wikipedia/en/2/2f/Cebu_Technological_University.png'">
                    <div>
                        <h1 class="text-xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-red-700 to-red-900 leading-tight">CTU Smart Violation</h1>
                        <p class="text-xs text-gray-500 font-semibold tracking-widest uppercase">Cebu Technological University</p>
                    </div>
                </div>

                <!-- Auth Buttons -->
                <nav class="flex items-center gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-6 py-2.5 bg-red-700 text-white rounded-xl text-sm font-bold hover:bg-red-800 transition shadow-lg hover:shadow-red-500/30">
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-5 py-2.5 text-gray-600 font-bold hover:text-red-700 transition text-sm">
                            Log in
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('login') }}" class="px-6 py-2.5 bg-red-700 text-white rounded-xl text-sm font-bold hover:bg-red-800 transition shadow-lg hover:shadow-red-500/30">
                                Register
                            </a>
                        @endif
                    @endauth
                </nav>
            </div>
        </header>

        <!-- Main Hero Section with Campus Background -->
        <!-- Notice the inline style for the background image and the relative positioning -->
        <main class="relative flex-grow flex items-center px-4 sm:px-6 lg:px-8 pt-32 pb-24 lg:pt-48 lg:pb-32 bg-cover bg-center bg-no-repeat" style="background-image: url('{{ asset('images/campus.jpg') }}');">
            
            <!-- The Dark Overlay (This makes the text readable) -->
            <div class="absolute inset-0 bg-gray-900/70 backdrop-blur-sm"></div>

            <div class="relative z-10 max-w-7xl mx-auto w-full grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-8 items-center">
                
                <!-- Left Side: Text & CTA (Now colored for a dark background) -->
                <div class="space-y-8 text-center lg:text-left">
                    <span class="inline-flex items-center gap-2 py-1.5 px-4 rounded-full bg-red-600/20 border border-red-500/30 text-red-300 text-xs font-bold tracking-wide uppercase shadow-sm backdrop-blur-md">
                        <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
                        Official Student Portal
                    </span>

                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white tracking-tight leading-[1.1] drop-shadow-lg">
                        A Smarter Way to <br class="hidden lg:block"/>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-400 to-yellow-400">Manage Violations.</span>
                    </h1>
                    
                    <p class="text-lg text-gray-200 max-w-xl mx-auto lg:mx-0 leading-relaxed drop-shadow-md">
                        Welcome to the CTU Smart Violation System. A centralized, transparent platform designed to streamline reporting, tracking, and resolving student violations efficiently.
                    </p>

                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 pt-2">
                        <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-3.5 bg-red-600 text-white rounded-xl text-lg font-bold hover:bg-red-700 transition-all shadow-lg hover:shadow-red-500/50 transform hover:-translate-y-1">
                            Get Started
                        </a>
                        <a href="#features" class="w-full sm:w-auto px-8 py-3.5 bg-white/10 text-white border border-white/20 rounded-xl text-lg font-bold hover:bg-white/20 backdrop-blur-md transition-all shadow-sm">
                            Explore Features
                        </a>
                    </div>
                </div>

                <!-- Right Side: Floating Glass Mockup Card -->
                <div class="relative lg:ml-10 hidden lg:block">
                    <!-- Glow behind the card -->
                    <div class="absolute inset-0 bg-gradient-to-tr from-red-500 to-yellow-500 rounded-3xl transform rotate-3 scale-105 opacity-30 blur-xl"></div>
                    
                    <!-- The Card (Glassmorphism effect) -->
                    <div class="relative bg-white/95 backdrop-blur-xl p-6 rounded-3xl shadow-2xl border border-white/40 transform transition-transform hover:-translate-y-2 duration-300">
                        <!-- Mockup Header -->
                        <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-red-700 font-bold">JD</div>
                                <div>
                                    <h4 class="text-sm font-bold text-gray-900">John Doe</h4>
                                    <p class="text-xs text-gray-500">BSIT - 3rd Year</p>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 text-xs font-bold bg-yellow-100 text-yellow-800 rounded-md">Status: Pending</span>
                        </div>
                        <!-- Mockup Content -->
                        <div class="space-y-3">
                            <div class="h-2 bg-gray-100 rounded-full w-3/4"></div>
                            <div class="h-2 bg-gray-100 rounded-full w-1/2"></div>
                            <div class="pt-4">
                                <div class="p-3 bg-red-50 rounded-xl border border-red-100 flex items-start gap-3">
                                    <svg class="w-5 h-5 text-red-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    <div>
                                        <p class="text-sm font-bold text-red-800">Violation Detected</p>
                                        <p class="text-xs text-red-600">Improper Uniform Policy</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </main>

        <!-- Features Section -->
        <section id="features" class="bg-white py-24 border-t border-gray-100 relative z-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-4">Everything you need to maintain discipline</h2>
                    <p class="text-lg text-gray-600">Our system provides a seamless experience for both students and administrators.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="p-8 rounded-2xl bg-gray-50 border border-gray-100 hover:shadow-lg transition-shadow duration-300">
                        <div class="w-14 h-14 bg-red-100 rounded-xl flex items-center justify-center mb-6 text-red-700">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Easy Reporting</h3>
                        <p class="text-gray-600 leading-relaxed">Quickly file and submit reports for student violations with our intuitive digital forms.</p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="p-8 rounded-2xl bg-gray-50 border border-gray-100 hover:shadow-lg transition-shadow duration-300">
                        <div class="w-14 h-14 bg-red-100 rounded-xl flex items-center justify-center mb-6 text-red-700">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Secure Records</h3>
                        <p class="text-gray-600 leading-relaxed">All student data and violation histories are encrypted and securely stored in our database.</p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="p-8 rounded-2xl bg-gray-50 border border-gray-100 hover:shadow-lg transition-shadow duration-300">
                        <div class="w-14 h-14 bg-red-100 rounded-xl flex items-center justify-center mb-6 text-red-700">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Real-time Tracking</h3>
                        <p class="text-gray-600 leading-relaxed">Monitor the status of cases in real-time, from initial report to final resolution.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Simple Footer -->
        <footer class="bg-gray-900 py-8 text-center text-sm text-gray-400 relative z-10">
            <p>&copy; {{ date('Y') }} Cebu Technological University. All rights reserved.</p>
        </footer>

    </body>
</html>