<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manajemen Laporan - Gamaku WebGIS</title>
      <!-- Custom Fonts -->
    <link href="{{ asset('css/fonts.css') }}" rel="stylesheet">
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
</head>
<body class="antialiased bg-gray-50">
    <div class="relative min-h-screen">
        <!-- Header/Navigation -->
        <nav class="bg-[#083d62] shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">                <div class="flex justify-between h-16">
                    <div class="flex items-center space-x-2">
                        @if(file_exists(public_path('images/logo kuningg.png')))
                            <img src="{{ asset('images/logo kuningg.png') }}" alt="Gamaku Logo" class="h-12 w-auto object-contain"/>
                        @endif
                        <h1 class="text-2xl font-bold text-[#fdcb2c]">Gamaku WebGIS</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="/" class="text-gray-300 hover:text-white active:text-[#fdcb2c] px-3 py-2 text-sm font-medium">Beranda</a>                        <a href="{{ route('map') }}" class="text-gray-300 hover:text-white active:text-[#fdcb2c] px-3 py-2 text-sm font-medium">Peta</a>
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" @click.away="open = false"
                                class="text-gray-300 hover:text-white active:text-[#fdcb2c] px-3 py-2 text-sm font-medium flex items-center focus:outline-none">
                                Tabel
                                <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="open" x-transition
                                class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg z-50">
                                <a href="/info" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Tabel Titik Bangunan</a>
                                <a href="/infojalan" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Tabel Polygon Jalan</a>
                                <a href="/infobangunan" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Tabel Polygon Bangunan</a>
                            </div>
                        </div>
                        <a href="/management" class="text-[#fdcb2c] hover:text-white active:text-[#fdcb2c] px-3 py-2 text-sm font-medium">Laporkan Kerusakan</a>
                        @auth
                        {{-- Jika user login --}}
                        <div class="flex items-center space-x-6">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="bg-red-600 text-white hover:bg-red-700 px-4 py-2 rounded-md text-sm font-medium">
                                    Logout
                                </button>
                            </form>

                            <div class="flex items-center text-sm font-medium text-white space-x-2">
                                <i class="fa-solid fa-user"></i>
                                <span>{{ Auth::user()->name }}</span>
                            </div>
                        </div>
                        @else
                        {{-- Jika user belum login --}}
                        <a href="{{ route('login') }}" class="bg-white text-[#083d62] hover:bg-gray-100 active:bg-[#fdcb2c] px-4 py-2 rounded-md text-sm font-medium">
                            Masuk/Daftar
                        </a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>        <!-- Main Content with Background -->
        <main>
            <div class="relative min-h-[80vh]">
                <!-- Background image container -->
                <div class="absolute inset-0 z-0">
                    <img src="/UGM.jpeg" alt="UGM Background" class="w-full h-full object-cover">
                </div>
                
                <!-- Content with glassmorphism -->
                <div class="relative z-10">
                    <div class="max-w-5xl mx-auto py-24 px-4 sm:px-6 lg:px-8">
                        <div class="backdrop-blur-md bg-white/30 rounded-2xl p-8 shadow-xl border border-white/20">
                            <div class="text-center">
                                <h2 class="text-2xl font-bold text-[#083d62] sm:text-4xl font-serif">
                                    Pilih aksi yang ingin Anda lakukan terkait laporan kerusakan
                                </h2>
                                
                                <!-- Action Cards -->
                                <div class="mt-12">
                                    <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 max-w-3xl mx-auto">
                                        <!-- Status Laporan Card -->
                                        <div class="group">
                                            <a href="/report" class="block">
                                                <div class="backdrop-blur-md bg-white/90 rounded-xl p-8 shadow-xl border border-white/20 transition duration-300 hover:bg-white/95 hover:shadow-2xl hover:-translate-y-1">
                                                    <div class="flex flex-col items-center">
                                                        <svg class="w-12 h-12 text-[#083d62] mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                                        </svg>
                                                        <h3 class="text-2xl font-semibold text-[#083d62] mb-2">Laporkan Kerusakan</h3>
                                                        <p class="text-gray-600">Buat laporan kerusakan baru</p>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>

                                        <!-- Laporkan Kerusakan Card -->
                                        <div class="group">
                                            <a href="/status-report" class="block">
                                                <div class="backdrop-blur-md bg-white/90 rounded-xl p-8 shadow-xl border border-white/20 transition duration-300 hover:bg-white/95 hover:shadow-2xl hover:-translate-y-1">
                                                    <div class="flex flex-col items-center">
                                                        <svg class="w-12 h-12 text-[#083d62] mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        <h3 class="text-2xl font-semibold text-[#083d62] mb-2">Status Pelaporan</h3>
                                                        <p class="text-gray-600">Lihat status laporan yang telah dibuat</p>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-gray-800">
            <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <p class="text-base text-gray-400">
                        © 2025 Gamaku WebGIS. All rights reserved.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>