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

</head>

<body class="antialiased bg-gray-50 font-sans">
    <div class="relative min-h-screen flex flex-col">
        <!-- Header/Navigation -->
        <nav class="bg-[#083d62] shadow-sm">
            <div class="max-w-7xl mx-auto px-2 sm:px-4 lg:px-8">
                <div class="flex flex-col sm:flex-row justify-between h-auto sm:h-16 py-2 sm:py-0">
                    <div class="flex items-center space-x-2 mb-2 sm:mb-0">
                        @if(file_exists(public_path('images/logo kuningg.png')))
                        <img src="{{ asset('images/logo kuningg.png') }}" alt="Gamaku Logo" class="h-10 sm:h-12 w-auto object-contain" />
                        @endif
                        <h1 class="text-xl sm:text-2xl font-bold text-[#fdcb2c]">Gamaku WebGIS</h1>
                    </div>
                    <div class="flex flex-wrap items-center space-x-2 sm:space-x-4">
                        <a href="/" class="text-[#fdcb2c] px-2 sm:px-3 py-1 sm:py-2 text-xs sm:text-sm font-medium"><i class="fa-solid fa-house"></i> Beranda</a>
                        <a href="/map" class="text-gray-300 hover:text-white active:text-[#fdcb2c] px-2 sm:px-3 py-1 sm:py-2 text-xs sm:text-sm font-medium flex items-center focus:outline-none"><i class="fa-solid fa-map mr-1"></i> Peta</a>
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" @click.away="open = false"
                                class="text-gray-300 hover:text-white active:text-[#fdcb2c] px-2 sm:px-3 py-1 sm:py-2 text-xs sm:text-sm font-medium flex items-center focus:outline-none">
                                <i class="fa-solid fa-table mr-1"></i>Tabel
                                <svg class="ml-1 w-3 sm:w-4 h-3 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="open" x-transition
                                class="absolute right-0 mt-2 w-40 sm:w-48 bg-white border border-gray-200 rounded-md shadow-lg z-50">
                                <a href="/info" class="block px-4 py-2 text-xs sm:text-sm text-gray-700 hover:bg-gray-100"><i class="fa-solid fa-location-dot"></i> Tabel Titik Bangunan</a>
                                <a href="/infojalan" class="block px-4 py-2 text-xs sm:text-sm text-gray-700 hover:bg-gray-100"><i class="fa-solid fa-road"></i> Tabel Polygon Jalan</a>
                                <a href="/infobangunan" class="block px-4 py-2 text-xs sm:text-sm text-gray-700 hover:bg-gray-100"><i class="fa-solid fa-draw-polygon"></i> Tabel Polygon Bangunan</a>
                            </div>
                        </div>
                        <a href="/management" class="text-gray-300 hover:text-white active:text-[#fdcb2c] px-2 sm:px-3 py-1 sm:py-2 text-xs sm:text-sm font-medium"><i class="fa-solid fa-flag"></i> Laporkan Kerusakan</a>
                        @auth
                        <div class="flex items-center space-x-2 sm:space-x-6">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="bg-red-600 text-white hover:bg-red-700 px-2 sm:px-4 py-1 sm:py-2 rounded-md text-xs sm:text-sm font-medium">
                                    Logout
                                </button>
                            </form>
                            <div class="flex items-center text-xs sm:text-sm font-medium text-white space-x-2">
                                <i class="fa-solid fa-user"></i>
                                <span>{{ Auth::user()->name }}</span>
                            </div>
                        </div>
                        @else
                        <a href="{{ route('login') }}" class="bg-white text-[#083d62] hover:bg-gray-100 active:bg-[#fdcb2c] px-2 sm:px-4 py-1 sm:py-2 rounded-md text-xs sm:text-sm font-medium">
                            Masuk/Daftar
                        </a>
                        @endauth
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
                                Navigasi Peta Interaktif
                            </h3>
                            <p class="mt-1 sm:mt-2 text-sm sm:text-base text-gray-500">
                                Jelajahi kampus dengan peta interaktif yang memuat informasi detail setiap jalan dan bangunan.
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