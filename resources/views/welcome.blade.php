<!DOCTYPE html><html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth overflow-x-hidden"><head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CTU Smart Violation - Home</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        
        .bg-gold-gradient {
            background: linear-gradient(135deg, #d9a74a 0%, #b8860b 100%);
        }

        /* Custom Keyframe Animations for Mobile Menu Items Stagger */
        @keyframes drawerItemSlide {
            0% {
                opacity: 0;
                transform: translateY(-12px) scale(0.97);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .animate-drawer-item {
            animation: drawerItemSlide 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style></head><body class="font-sans antialiased text-gray-800 bg-white min-h-screen relative overflow-x-hidden flex flex-col" x-data="{ mobileMenuOpen: false }">

    <!-- TOP-LEFT GOLDEN BRAND SHAPE WITH LOGO -->
    <div class="absolute top-0 left-0 z-30 pointer-events-none">
        <div class="relative w-40 h-40 sm:w-56 sm:h-56 bg-gold-gradient rounded-br-[100%] shadow-lg flex items-start justify-start p-4 sm:p-6">
            <img src="{{ asset('images/CTU-Logo.png') }}" alt="CTU Logo" class="h-14 w-14 sm:h-20 sm:w-20 object-contain drop-shadow-md pointer-events-auto">
        </div>
    </div>

    <!-- TOP-LEVEL WRAPPER -->
    <div class="relative w-full flex-1 flex flex-col overflow-x-hidden">
        
        <!-- HEADER / NAVIGATION BAR -->
        <header class="relative z-30 w-full max-w-7xl mx-auto px-4 sm:px-12 py-6 flex items-center justify-between">
            <!-- Spacer for top-left logo -->
            <div class="w-32 sm:w-48"></div>

            <!-- Desktop Navigation Links -->
            <nav class="hidden md:flex items-center space-x-8 lg:space-x-12">
                <a href="{{ url('/') }}" class="text-sm font-semibold text-gray-900 hover:text-red-700 transition">Home</a>
                <a href="#about" class="text-sm font-semibold text-gray-600 hover:text-red-700 transition">About</a>
                <a href="#features" class="text-sm font-semibold text-gray-600 hover:text-red-700 transition">Features</a>
                <a href="#contact" class="text-sm font-semibold text-gray-600 hover:text-red-700 transition">Contact</a>
            </nav>

            <!-- Mobile Hamburger Button with Animated Morph Icon -->
            <div class="md:hidden flex items-center z-40">
                <button @click="mobileMenuOpen = !mobileMenuOpen" 
                        aria-label="Toggle Navigation Menu"
                        class="p-2.5 text-gray-700 hover:text-red-700 focus:outline-none bg-gray-50/90 hover:bg-gray-100 rounded-xl border border-gray-100 shadow-sm transition-all duration-200 active:scale-90">
                    <svg class="w-6 h-6 transition-transform duration-300" :class="{ 'rotate-90': mobileMenuOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path x-show="mobileMenuOpen" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </header>

        <!-- ULTRA-SMOOTH SPRING TRANSITION MOBILE NAVIGATION DRAWER -->
        <div x-show="mobileMenuOpen" x-cloak class="fixed inset-0 z-50 md:hidden flex flex-col">
            <!-- Backdrop Overlay with Blur & Fade -->
            <div @click="mobileMenuOpen = false" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 backdrop-blur-none"
                 x-transition:enter-end="opacity-100 backdrop-blur-sm"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 backdrop-blur-sm"
                 x-transition:leave-end="opacity-0 backdrop-blur-none"
                 class="absolute inset-0 bg-gray-900/60"></div>

            <!-- Slide & Spring Drawer Panel -->
            <div x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-500 transform"
                 x-transition:enter-start="-translate-y-full opacity-0 scale-95"
                 x-transition:enter-end="translate-y-0 opacity-100 scale-100"
                 x-transition:leave="transition cubic-bezier(0.4, 0, 1, 1) duration-300 transform"
                 x-transition:leave-start="translate-y-0 opacity-100 scale-100"
                 x-transition:leave-end="-translate-y-full opacity-0 scale-95"
                 class="relative w-full bg-white/95 backdrop-blur-md rounded-b-3xl shadow-2xl border-b border-gray-100/80 p-6 space-y-6 z-10">
                
                <!-- Drawer Header / Brand -->
                <div class="flex items-center justify-between pb-4 border-b border-gray-100/80">
                    <div class="flex items-center space-x-3">
                        <img src="{{ asset('images/CTU-Logo.png') }}" alt="CTU Logo" class="h-10 w-10 object-contain drop-shadow-sm">
                        <span class="font-bold text-gray-900 text-sm tracking-tight">CTU Smart Violation</span>
                    </div>
                    <button @click="mobileMenuOpen = false" class="p-2 text-gray-400 hover:text-gray-700 rounded-full hover:bg-gray-100 active:scale-90 transition duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Navigation Links List with Staggered Cascading Animation -->
                <nav class="space-y-2">
                    <a href="{{ url('/') }}" 
                       @click="mobileMenuOpen = false" 
                       :class="{ 'animate-drawer-item': mobileMenuOpen }"
                       style="animation-delay: 70ms;"
                       class="flex items-center px-4 py-3 rounded-xl text-sm font-semibold text-gray-800 hover:bg-red-50 hover:text-red-700 active:scale-[0.98] transition-all duration-200">
                         Home
                    </a>
                    <a href="#about" 
                       @click="mobileMenuOpen = false" 
                       :class="{ 'animate-drawer-item': mobileMenuOpen }"
                       style="animation-delay: 110ms;"
                       class="flex items-center px-4 py-3 rounded-xl text-sm font-semibold text-gray-600 hover:bg-red-50 hover:text-red-700 active:scale-[0.98] transition-all duration-200">
                         About
                    </a>
                    <a href="#features" 
                       @click="mobileMenuOpen = false" 
                       :class="{ 'animate-drawer-item': mobileMenuOpen }"
                       style="animation-delay: 150ms;"
                       class="flex items-center px-4 py-3 rounded-xl text-sm font-semibold text-gray-600 hover:bg-red-50 hover:text-red-700 active:scale-[0.98] transition-all duration-200">
                         Features
                    </a>
                    <a href="#contact" 
                       @click="mobileMenuOpen = false" 
                       :class="{ 'animate-drawer-item': mobileMenuOpen }"
                       style="animation-delay: 190ms;"
                       class="flex items-center px-4 py-3 rounded-xl text-sm font-semibold text-gray-600 hover:bg-red-50 hover:text-red-700 active:scale-[0.98] transition-all duration-200">
                         Contact
                    </a>
                </nav>

                <!-- Mobile Auth Action CTA -->
                <div class="pt-2 border-t border-gray-100/80"
                     :class="{ 'animate-drawer-item': mobileMenuOpen }"
                     style="animation-delay: 230ms;">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="block w-full text-center py-3.5 rounded-xl bg-red-700 hover:bg-red-800 text-white text-sm font-bold shadow-md hover:shadow-lg transition-all duration-200 active:scale-95">
                                Go to Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="block w-full text-center py-3.5 rounded-xl bg-red-700 hover:bg-red-800 text-white text-sm font-bold shadow-md hover:shadow-lg transition-all duration-200 active:scale-95">
                                Get Started
                            </a>
                        @endauth
                    @endif
                </div>

            </div>
        </div>

        <!-- HERO SECTION BODY -->
        <main class="relative z-10 w-full max-w-7xl mx-auto px-6 sm:px-12 min-h-[calc(100vh-100px)] grid grid-cols-1 lg:grid-cols-12 items-center py-10 lg:py-0 gap-8">
            
            <!-- LEFT TEXT CONTENT -->
            <div class="w-full lg:col-span-6 space-y-6 text-left pt-12 sm:pt-16 lg:pt-0 z-20">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-gray-900 tracking-tight leading-[1.15]">
                    Manage Campus <br />Violations
                </h1>

                <p class="text-gray-600 text-sm sm:text-base max-w-md leading-relaxed font-normal">
                    A web-based system that helps CTU-Danao record, track, and manage student violations quickly and accurately.
                </p>

                <div class="pt-2">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" 
                               class="inline-block px-7 py-3.5 rounded-xl bg-red-700 hover:bg-red-800 text-white font-bold text-sm shadow-md transition-all active:scale-95">
                                Go to Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" 
                               class="inline-block px-7 py-3.5 rounded-xl bg-red-700 hover:bg-red-800 text-white font-bold text-sm shadow-md transition-all active:scale-95">
                                Get Started
                            </a>
                        @endauth
                    @endif
                </div>
            </div>

            <!-- RIGHT GRAPHIC -->
            <div class="w-full lg:col-span-6 relative min-h-[350px] sm:min-h-[480px] lg:min-h-[580px] flex items-center justify-end overflow-hidden">
                <div class="absolute inset-y-0 right-0 w-full bg-white z-0 pointer-events-none shadow-2xl overflow-hidden"
                     style="clip-path: polygon(25% 0%, 100% 0%, 100% 100%, 0% 100%);">
                    <img src="{{ asset('images/campus.jpg') }}" 
                         alt="CTU Danao Campus" 
                         class="w-full h-full object-cover object-center">
                </div>
            </div>

        </main> 

        <!-- ABOUT SECTION -->
        <section id="about" class="w-full bg-white py-24 px-6 sm:px-12">
            <div class="max-w-3xl mx-auto text-center space-y-4">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">About</h2>
                <p class="text-gray-600 text-sm sm:text-base leading-relaxed">
                    The Smart Violation Management System is a web-based platform that helps Cebu Technological University, Danao Campus record, monitor, and manage student violations in one place. It supports the campus goal of promoting discipline through organized and reliable records.
                </p>
            </div>
        </section>

        <!-- THE PROBLEM SECTION -->
        <section class="w-full bg-white py-12 sm:py-24 overflow-hidden">
            <div class="max-w-7xl mx-auto px-6 sm:px-12 grid grid-cols-1 lg:grid-cols-12 items-center gap-8 lg:gap-12">
                
                <!-- Left Text -->
                <div class="lg:col-span-5 space-y-3 sm:space-y-4 text-left z-10">
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">The Problem</h2>
                    <p class="text-gray-600 text-sm sm:text-base leading-relaxed">
                        The Smart Violation Management System is a web-based platform that helps Cebu Technological University, Danao Campus record, monitor, and manage student violations in one place. It supports the campus goal of promoting discipline through organized and reliable records.
                    </p>
                </div>

                <!-- Right Side Gold Backdrop (Image Aligned to Left-Bottom) -->
                <div class="lg:col-span-7 relative flex items-center justify-end">
                    <div class="w-full lg:w-[115%] lg:-mr-[15%] rounded-3xl lg:rounded-r-none lg:rounded-l-[40px] pt-16 pb-8 sm:py-24 lg:py-28 px-4 sm:pl-10 sm:pr-28 shadow-inner flex items-end justify-start"
                         style="background: linear-gradient(180deg, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 0.3) 10%, #e2b65e 30%, #d8a33f 100%);">
                        <div class="rounded-xl sm:rounded-2xl overflow-hidden border-2 border-[#f5db96] shadow-2xl bg-white w-full max-w-lg mr-auto ml-0">
                            <img src="{{ asset('images/About.png') }}" 
                                 alt="The Problem" 
                                 class="w-full h-auto object-cover block">
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <!-- THE SOLUTION / FEATURES SECTION -->
        <section id="features" class="w-full bg-white py-12 sm:py-24 px-6 sm:px-12">
            <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 items-center gap-8 lg:gap-12">
                
                <!-- Dashboard Image -->
                <div class="w-full lg:col-span-7 order-2 lg:order-1">
                    <div class="rounded-xl sm:rounded-2xl overflow-hidden shadow-xl sm:shadow-2xl bg-white border border-gray-100 p-1.5 sm:p-2.5">
                        <img src="{{ asset('images/features.png') }}" 
                             alt="The Solution" 
                             loading="lazy"
                             class="w-full h-auto object-contain rounded-lg sm:rounded-xl">
                    </div>
                </div>

                <!-- Text Content -->
                <div class="w-full lg:col-span-5 space-y-3 sm:space-y-4 text-left order-1 lg:order-2">
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">The Solution</h2>
                    <p class="text-gray-600 text-sm sm:text-base leading-relaxed">
                        The system replaces paper-based and manual records with a digital process that is faster, more accurate, and easier to access. Every violation is recorded, stored, and tracked in a single system.
                    </p>
                </div>

            </div>
        </section>

        <!-- WHO IS IT FOR? SECTION (MOBILE TABS WITH BLACK TEXT/ICONS + DESKTOP GRID) -->
        <section class="w-full pt-6" x-data="{ activeRole: 'students' }">
            <div class="max-w-7xl mx-auto px-6 sm:px-12 text-center mb-8">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">
                    Who is it for?
                </h2>
            </div>

            <div class="w-full bg-red-700 py-12 sm:py-20 px-6 sm:px-12">
                <div class="max-w-6xl mx-auto">
                    
                    <!-- MOBILE TAB SELECTOR (Visible only on mobile/tablet below md) -->
                    <div class="flex md:hidden bg-red-900/50 p-1.5 rounded-2xl mb-6 backdrop-blur-sm border border-red-600/45">
                        <button @click="activeRole = 'students'"
                                :class="activeRole === 'students' ? 'bg-white text-black shadow-md font-bold' : 'text-white hover:text-gray-200 font-medium'"
                                class="flex-1 py-2.5 px-3 rounded-xl text-xs transition-all duration-200">
                            Students
                        </button>
                        <button @click="activeRole = 'officers'"
                                :class="activeRole === 'officers' ? 'bg-white text-black shadow-md font-bold' : 'text-white hover:text-gray-200 font-medium'"
                                class="flex-1 py-2.5 px-3 rounded-xl text-xs transition-all duration-200">
                            Officers
                        </button>
                        <button @click="activeRole = 'admins'"
                                :class="activeRole === 'admins' ? 'bg-white text-black shadow-md font-bold' : 'text-white hover:text-gray-200 font-medium'"
                                class="flex-1 py-2.5 px-3 rounded-xl text-xs transition-all duration-200">
                            Admins
                        </button>
                    </div>

                    <!-- CARDS CONTAINER -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
                        
                        <!-- Students -->
                        <div :class="activeRole === 'students' ? 'block' : 'hidden md:flex'"
                             class="bg-[#f8f8f8] rounded-2xl p-6 sm:p-8 shadow-lg hover:-translate-y-1 transition-all duration-300 flex-col justify-between">
                            <div>
                                <div class="w-12 h-12 mb-4 sm:mb-5 text-black bg-gray-200 rounded-xl flex items-center justify-center">
                                    <svg class="w-7 h-7" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 3L1 9l4 2.18v6L12 21l7-3.82v-6l2-1.09V17h2V9L12 3zm6.82 6L12 12.72 5.18 9 12 5.28 18.82 9zM17 15.99l-5 2.73-5-2.73v-3.72L12 15l5-2.73v3.72z"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg sm:text-xl font-bold text-black mb-2">Students</h3>
                                <p class="text-black text-xs sm:text-sm leading-relaxed font-normal">
                                    CTU Danao students can view their own violation records and their status anytime.
                                </p>
                            </div>
                        </div>

                        <!-- Discipline Officers -->
                        <div :class="activeRole === 'officers' ? 'block' : 'hidden md:flex'"
                             class="bg-[#f8f8f8] rounded-2xl p-6 sm:p-8 shadow-lg hover:-translate-y-1 transition-all duration-300 flex-col justify-between">
                            <div>
                                <div class="w-12 h-12 mb-4 sm:mb-5 text-black bg-gray-200 rounded-xl flex items-center justify-center">
                                    <svg class="w-7 h-7" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 4c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg sm:text-xl font-bold text-black mb-2">Discipline Officers</h3>
                                <p class="text-black text-xs sm:text-sm leading-relaxed font-normal">
                                    Record violations quickly and accurately, with complete and consistent details.
                                </p>
                            </div>
                        </div>

                        <!-- Administrators -->
                        <div :class="activeRole === 'admins' ? 'block' : 'hidden md:flex'"
                             class="bg-[#f8f8f8] rounded-2xl p-6 sm:p-8 shadow-lg hover:-translate-y-1 transition-all duration-300 flex-col justify-between">
                            <div>
                                <div class="w-12 h-12 mb-4 sm:mb-5 text-black bg-gray-200 rounded-xl flex items-center justify-center">
                                    <svg class="w-7 h-7" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58a.49.49 0 00.12-.61l-1.92-3.32a.488.488 0 00-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54a.484.484 0 00-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58a.49.49 0 00-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg sm:text-xl font-bold text-black mb-2">Administrators</h3>
                                <p class="text-black text-xs sm:text-sm leading-relaxed font-normal">
                                    Monitor cases, manage records, and generate reports to support decision-making.
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>

        <!-- CONTACT / FOOTER SECTION WITH CSS/SVG ICONS -->
        <footer id="contact" class="relative z-10 w-full py-12 px-6 sm:px-12 bg-gray-900 text-gray-300 border-t border-gray-800">
            <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 items-center mb-8">
                
                <!-- Brand / Info -->
                <div class="space-y-2 text-center md:text-left">
                    <h3 class="text-white font-bold text-base tracking-tight">CTU-Danao Campus</h3>
                    <p class="text-xs text-gray-400">Smart Violation Management System</p>
                </div>

                <!-- Contact Details with Inline SVG / CSS Icons -->
                <div class="flex flex-col sm:flex-row justify-center items-center gap-4 sm:gap-6 text-xs">
                    <!-- Email Icon -->
                    <a href="mailto:info@ctu.edu.ph" class="flex items-center space-x-2 hover:text-white transition duration-200">
                        <span class="p-2 bg-gray-800 rounded-full text-red-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </span>
                        <span>info@ctu.edu.ph</span>
                    </a>

                    <!-- Phone Icon -->
                    <a href="tel:+63321234567" class="flex items-center space-x-2 hover:text-white transition duration-200">
                        <span class="p-2 bg-gray-800 rounded-full text-red-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </span>
                        <span>(032) 123-4567</span>
                    </a>
                </div>

                <!-- Location / Map Icon -->
                <div class="flex justify-center md:justify-end items-center space-x-2 text-xs text-gray-400">
                    <span class="p-2 bg-gray-800 rounded-full text-red-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </span>
                    <span>Danao City, Cebu, Philippines</span>
                </div>

            </div>

            <!-- Copyright Line -->
            <div class="border-t border-gray-800 pt-6 text-center text-xs text-gray-500">
                <p>&copy; {{ date('Y') }} CTU-Danao Campus Smart Violation System. All rights reserved.</p>
            </div>
        </footer>

    </div></body></html>