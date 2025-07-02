<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Status Laporan - Gamaku WebGIS</title>
    
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
    <div class="min-h-screen flex flex-col">
        <!-- Header/Navigation -->
        <nav class="bg-[#083d62] shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center space-x-2">
                        @if(file_exists(public_path('images/logo kuningg.png')))
                            <img src="{{ asset('images/logo kuningg.png') }}" alt="Gamaku Logo" class="h-12 w-auto object-contain"/>
                        @endif
                        <h1 class="text-2xl font-bold text-[#fdcb2c]">Gamaku WebGIS</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="/" class="text-gray-300 hover:text-white active:text-[#fdcb2c] px-3 py-2 text-sm font-medium">Beranda</a>
                        <a href="{{ route('map') }}" class="text-gray-300 hover:text-white active:text-[#fdcb2c] px-3 py-2 text-sm font-medium">Peta</a>
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" @click.away="open = false"
                                class="text-gray-300 hover:text-white active:text-[#fdcb2c] px-3 py-2 text-sm font-medium flex items-center focus:outline-none">
                                Informasi
                                <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="open" x-transition
                                class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg z-50">
                                <a href="/info" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Info Titik Bangunan</a>
                                <a href="/infojalan" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Info Jalan</a>
                                <a href="/infobangunan" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Info Bangunan</a>
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
        </nav>

        <!-- Main Content -->
        <main class="flex-grow">
            <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                @auth
                    <!-- Header -->
                    <div class="px-4 py-6 sm:px-0">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-3xl font-bold text-gray-900 font-serif">Status Laporan Kerusakan</h2>
                                <p class="mt-2 text-gray-600">Pantau status laporan kerusakan yang telah Anda ajukan</p>
                            </div>
                        </div>
                    </div>

                    @if($reports->isEmpty())
                        <div class="text-center py-12">
                            <div class="rounded-lg bg-white p-8 shadow-sm">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada laporan</h3>
                                <p class="mt-1 text-sm text-gray-500">Anda belum membuat laporan kerusakan apapun.</p>
                                <div class="mt-6">
                                    <a href="/report" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-[#083d62] hover:bg-[#083d62]/90">
                                        Buat Laporan Baru
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Status Cards -->
                        <div class="mt-8 grid gap-6 grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
                            @foreach($reports as $report)
                                <div class="bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-shadow duration-300">
                                    <div class="px-4 py-5 sm:p-6">
                                        <div class="flex items-center justify-between">
                                            <div class="flex-1">
                                                <h3 class="text-lg font-medium text-gray-900">Laporan #{{ $report->id }}</h3>
                                                <p class="mt-1 text-sm text-gray-500">{{ $report->created_at->format('d M Y, H:i') }}</p>
                                            </div>
                                            <div>
                                                @switch($report->status)
                                                    @case('pending')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            Menunggu Review
                                                        </span>
                                                        @break
                                                    @case('in_progress')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                            Sedang Ditangani
                                                        </span>
                                                        @break
                                                    @case('completed')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            Selesai
                                                        </span>
                                                        @break
                                                    @case('rejected')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                            Ditolak
                                                        </span>
                                                        @break
                                                    @default
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                            Status Tidak Diketahui
                                                        </span>
                                                @endswitch
                                            </div>
                                        </div>
                                        
                                        <div class="mt-4">
                                            <p class="text-sm text-gray-900 font-medium">Kategori:</p>
                                            <p class="text-sm text-gray-600">{{ ucfirst($report->category) }} - {{ $report->subcategory }}</p>
                                        </div>

                                        <div class="mt-4">
                                            <p class="text-sm text-gray-900 font-medium">Deskripsi:</p>
                                            <p class="text-sm text-gray-600">{{ Str::limit($report->description, 100) }}</p>
                                        </div>

                                        <div class="mt-4">
                                            <p class="text-sm text-gray-900 font-medium">Lokasi:</p>
                                            <a href="https://www.google.com/maps?q={{ $report->latitude }},{{ $report->longitude }}" 
                                               target="_blank" 
                                               class="text-sm text-[#083d62] hover:text-[#083d62]/80 font-medium">
                                                Lihat di Peta
                                                <span class="ml-1">→</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @else
                    <div class="text-center py-12">
                        <div class="rounded-lg bg-white p-8 shadow-sm">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Login diperlukan</h3>
                            <p class="mt-1 text-sm text-gray-500">Silakan login untuk melihat status laporan Anda.</p>
                            <div class="mt-6">
                                <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-[#083d62] hover:bg-[#083d62]/90">
                                    Login
                                </a>
                            </div>
                        </div>
                    </div>
                @endauth
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-gray-800">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
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
