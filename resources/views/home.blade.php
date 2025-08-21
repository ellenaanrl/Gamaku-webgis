<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gamaku WebGIS</title>
    <!-- Custom Fonts -->
    <link href="{{ asset('css/fonts.css') }}" rel="stylesheet">
    <!-- Font Awesome 6 Free -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Gama Sans', 'sans-serif'],
                        'serif': ['Gama Serif', 'serif'],
                    },
                },
            },
        }
    </script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const slides = document.querySelectorAll('.carousel-slide');
            const indicators = document.querySelectorAll('.carousel-indicator');
            let currentSlide = 0;
            const totalSlides = slides.length;

            function showSlide(index) {
                slides.forEach((slide, i) => {
                    slide.style.display = i === index ? 'block' : 'none';
                    indicators[i].classList.toggle('bg-opacity-70', i === index);
                    indicators[i].classList.toggle('bg-opacity-40', i !== index);
                });
            }

            function nextSlide() {
                currentSlide = (currentSlide + 1) % totalSlides;
                showSlide(currentSlide);
            }

            function prevSlide() {
                currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
                showSlide(currentSlide);
            }

            // Inisialisasi pertama
            showSlide(currentSlide);

            // Kontrol tombol manual
            document.getElementById('next-banner').addEventListener('click', nextSlide);
            document.getElementById('prev-banner').addEventListener('click', prevSlide);

            // Auto slide setiap 5 detik
            setInterval(nextSlide, 4000);
        });
    </script>


</head>

<body class="antialiased bg-gray-50 font-sans">
    <div class="relative min-h-screen flex flex-col">
        <!-- Header/Navigation -->
        <nav class="bg-[#083d62] shadow-sm sticky top-0 z-40">
            <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo and Title -->
                    <div class="flex items-center space-x-2 min-w-0 flex-shrink-0">
                        @if(file_exists(public_path('images/logo kuningg.png')))
                        <img src="{{ asset('images/logo kuningg.png') }}" alt="Gamaku Logo" class="h-8 sm:h-12 w-auto object-contain flex-shrink-0" />
                        @endif
                        <h1 class="text-lg sm:text-2xl font-bold text-[#fdcb2c] truncate">Gamaku</h1>
                    </div>

                    <div class="hidden lg:flex items-center space-x-4">
                        <a href="/" class="text-[#fdcb2c] px-3 py-2 text-sm font-medium whitespace-nowrap">
                            <i class="fa-solid fa-house"></i> Beranda
                        </a>
                        <a href="/map" class="text-gray-300 hover:text-white active:text-[#fdcb2c] px-3 py-2 text-sm font-medium whitespace-nowrap">
                            <i class="fa-solid fa-map mr-1"></i>Peta
                        </a>
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" @click.away="open = false"
                                class="text-gray-300 hover:text-white active:text-[#fdcb2c] px-2 sm:px-3 py-1 sm:py-2 text-xs sm:text-sm font-medium flex items-center focus:outline-none">
                                <i class="fa-solid fa-table mr-1"></i>Tabel
                                <svg class="ml-1 w-3 sm:w-4 h-3 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="open" x-transition
                                class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg z-50">
                                <a href="/info" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fa-solid fa-location-dot"></i> Tabel Titik Bangunan
                                </a>
                                <a href="/infojalan" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fa-solid fa-road"></i> Tabel informasi jalan
                                </a>
                            </div>
                        </div>
                        <a href="/management" class="text-gray-300 hover:text-white active:text-[#fdcb2c] px-3 py-2 text-sm font-medium whitespace-nowrap">
                            <i class="fa-solid fa-flag"></i> Laporkan Kerusakan
                        </a>

                        @auth
                        <div class="flex items-center space-x-4">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="bg-red-600 text-white hover:bg-red-700 px-4 py-2 rounded-md text-sm font-medium">
                                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                                </button>
                            </form>
                            <div class="flex items-center text-sm font-medium text-white space-x-2">
                                <i class="fa-solid fa-user"></i>
                                <span class="hidden xl:inline">{{ Auth::user()->name }}</span>
                            </div>
                        </div>
                        @else
                        <a href="{{ route('login') }}" class="bg-white text-[#083d62] hover:bg-gray-100 active:bg-[#fdcb2c] px-4 py-2 rounded-md text-sm font-medium whitespace-nowrap">
                            Masuk/Daftar
                        </a>
                        @endauth
                    </div>

                    <!-- Mobile Menu Button -->
                    <div class="lg:hidden" x-data="{ mobileOpen: false }">
                        <button @click="mobileOpen = !mobileOpen" class="text-gray-300 hover:text-white p-2">
                            <svg x-show="!mobileOpen" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <svg x-show="mobileOpen" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                        <!-- Mobile Menu -->
                        <div x-show="mobileOpen" x-transition @click.away="mobileOpen = false"
                            class="absolute top-16 left-0 right-0 bg-[#083d62] border-t border-[#0a4a75] shadow-lg z-50 mobile-menu">
                            <div class="px-4 py-2 space-y-2">
                                <a href="/" class="block text-[#fdcb2c] hover:bg-[#0a4a75] px-3 py-2 text-sm font-medium">
                                    <i class="fa-solid fa-house mr-2"></i>Beranda
                                </a>
                                <a href="/map" class="block text-gray-300 hover:text-white px-3 py-2 text-sm font-medium">
                                    <i class="fa-solid fa-map mr-2"></i>Peta
                                </a>
                                <div class="border-l-2 border-[#fdcb2c]">
                                    <div class="block text-gray-300 hover:text-white px-3 py-2 text-sm font-medium">
                                        <i class="fa-solid fa-table mr-2"></i>Tabel
                                    </div>
                                    <a href="/info" class="block text-gray-300 hover:bg-[#0a4a75] px-6 py-2 text-sm">
                                        <i class="fa-solid fa-location-dot mr-2"></i>Titik Bangunan
                                    </a>
                                    <a href="/infojalan" class="block text-gray-300 hover:bg-[#0a4a75] px-6 py-2 text-sm">
                                        <i class="fa-solid fa-road mr-2"></i>Polygon Jalan
                                    </a>
                                </div>
                                <a href="/management" class="block text-gray-300 hover:text-white px-3 py-2 text-sm font-medium">
                                    <i class="fa-solid fa-flag mr-2"></i>Laporkan Kerusakan
                                </a>

                                <div class="border-t border-[#0a4a75] pt-2">
                                    @auth
                                    <div class="flex items-center text-white px-3 py-2 text-sm">
                                        <i class="fa-solid fa-user mr-2"></i>{{ Auth::user()->name }}
                                    </div>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left bg-red-600 text-white hover:bg-red-700 px-3 py-2 text-sm font-medium rounded">
                                            <i class="fa-solid fa-right-from-bracket mr-2"></i>Logout
                                        </button>
                                    </form>
                                    @else
                                    <a href="{{ route('login') }}" class="block bg-white text-[#083d62] hover:bg-gray-100 px-3 py-2 text-sm font-medium rounded mx-3">
                                        Masuk/Daftar
                                    </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        <!-- Hero Section -->
        <div class="relative z-10 flex justify-center mt-4 sm:mt-8 px-2">
            <div id="banner-carousel" class="w-full max-w-5xl">
                <div class="relative overflow-hidden rounded-lg shadow-lg">
                    <!-- Slides -->
                    <div class="carousel-slide block" style="display: block;">
                        <img src="/UGM.jpeg" alt="Banner 1" class="w-full h-56 sm:h-[28rem] object-cover">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="bg-black bg-opacity-40 p-4 sm:p-8 rounded">
                                <h2 class="text-xl sm:text-3xl md:text-4xl font-bold text-white text-center">Welcome to GamaKu WebGIS</h2>
                                <p class="mt-2 sm:mt-4 text-white text-base sm:text-lg text-center">Sistem informasi jalan dan bangunan kampus UGM yang dirancang untuk mendukung pengelolaan jalan dan bangunan secara efisien, transparan, dan partisipatif.</p>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-slide hidden">
                        <img src="/ugm2.jpg" alt="Banner 2" class="w-full h-56 sm:h-[28rem] object-cover">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="bg-black bg-opacity-40 p-4 sm:p-8 rounded">
                                <h2 class="text-xl sm:text-3xl md:text-4xl font-bold text-white text-center">GamaKu hadir untuk memudahkan kita semua dalam melihat, mengenali, dan merawat kampus yang kita cintai.</h2>
                            </div>
                        </div>
                    </div>
                    <!-- Controls -->
                    <button id="prev-banner" class="absolute left-2 top-1/2 -translate-y-1/2 bg-gray-800 bg-opacity-50 text-white rounded-full p-2 hover:bg-opacity-80 focus:outline-none">
                        &#8592;
                    </button>
                    <button id="next-banner" class="absolute right-2 top-1/2 -translate-y-1/2 bg-gray-800 bg-opacity-50 text-white rounded-full p-2 hover:bg-opacity-80 focus:outline-none">
                        &#8594;
                    </button>
                    <!-- Indicators -->
                    <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex space-x-2">
                        <span class="carousel-indicator w-3 h-3 bg-white bg-opacity-70 rounded-full block border-2 border-white"></span>
                        <span class="carousel-indicator w-3 h-3 bg-white bg-opacity-40 rounded-full block border-2 border-white"></span>
                    </div>
                </div>
            </div>
        </div>
        <main>
            <!-- Features Section -->
            <div class="bg-white">
                <div class="max-w-7xl mx-auto py-8 sm:py-16 px-2 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-8 justify-center">
                        <!-- Feature 1 -->
                        <div class="bg-gray-50 rounded-lg p-4 sm:p-6">
                            <h3 class="text-base sm:text-lg font-medium text-gray-900 flex items-center gap-2">
                                <i class="fa-regular fa-compass"></i>
                                Peta Interaktif
                            </h3>
                            <p class="mt-1 sm:mt-2 text-sm sm:text-base text-gray-500">
                                Jelajahi kampus dengan peta interaktif yang memuat informasi setiap jalan dan bangunan.
                            </p>
                        </div>
                        <!-- Feature 2 -->
                        <div class="bg-gray-50 rounded-lg p-4 sm:p-6">
                            <h3 class="text-base sm:text-lg font-medium text-gray-900 flex items-center gap-2">
                                <i class="fa-solid fa-bullhorn"></i>
                                Pelaporan Kerusakan Cepat & Mudah
                            </h3>
                            <p class="mt-1 sm:mt-2 text-sm sm:text-base text-gray-500">
                                Laporkan kerusakan fasilitas kapan saja. Cukup isi formulir, sistem akan teruskan ke admin terkait.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <!-- Contact/Get in Touch Section -->
        <section class="bg-[#083d62] py-6 sm:py-8">
            <div class="max-w-4xl mx-auto px-2 sm:px-6 lg:px-8">
                <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6">
                    <h2 class="text-lg sm:text-xl font-bold text-[#083d62] mb-2 sm:mb-3 flex items-center gap-2">
                        <i class="fa-regular fa-envelope"></i>
                        Hubungi Kami
                    </h2>
                    <p class="text-gray-700 mb-2 sm:mb-4 text-sm sm:text-base">
                        Ada pertanyaan atau ingin berkolaborasi? Hubungi tim Gamaku WebGIS:
                    </p>
                    <ul class="space-y-1">
                        <li class="flex items-center gap-2">
                            <i class="fa-solid fa-envelope text-[#083d62]"></i>
                            <a href="mailto:Gamakuugm@gmail.com" class="text-[#083d62] hover:underline text-xs sm:text-base">Gamakuugm@gmail.com</a>
                        </li>
                    </ul>
                </div>
            </div>
        </section>
        <footer class="bg-gray-800">
            <div class="max-w-7xl mx-auto py-6 sm:py-12 px-2 sm:px-4 lg:px-8">
                <div class="text-center">
                    <p class="text-xs sm:text-base text-gray-400">
                        © 2025 Gamaku WebGIS. All rights reserved.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</body>

</html>