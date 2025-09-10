<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Informasi Jalan - Gamaku WebGIS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="{{ asset('css/fonts.css') }}" rel="stylesheet">
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

    <style>
        body {
            font-family: 'Gama Sans', sans-serif;
        }
    </style>
</head>

<body class="antialiased bg-gray-50">
    <div class="relative min-h-screen">
        <!-- Navbar -->
        <nav class="bg-[#083d62] shadow-sm sticky top-0 z-40">
            <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo and Title -->
                    <div class="flex items-center space-x-2 min-w-0 flex-shrink-0">
                        @if(file_exists(public_path('images/logo.png')))
                        <img src="{{ asset('images/logo.png') }}" alt="Gamaku Logo" class="h-8 sm:h-12 w-auto object-contain flex-shrink-0" />
                        @endif
                        <h1 class="text-lg sm:text-2xl font-bold text-[#fdcb2c] truncate">Gamaku</h1>
                    </div>

                    <!-- Desktop Navigation -->
                    <div class="hidden lg:flex items-center space-x-4">
                        <a href="/" class="text-gray-300 hover:text-white active:text-[#fdcb2c] px-3 py-2 text-sm font-medium whitespace-nowrap">
                            <i class="fa-solid fa-house"></i> Beranda
                        </a>
                        <a href="/map" class="text-gray-300 hover:text-white active:text-[#fdcb2c] px-3 py-2 text-sm font-medium whitespace-nowrap">
                            <i class="fa-solid fa-map mr-1"></i>Peta
                        </a>
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" @click.away="open = false"
                                class="text-[#fdcb2c] px-3 py-2 text-sm font-medium flex items-center focus:outline-none whitespace-nowrap">
                                <i class="fa-solid fa-table mr-1"></i>Tabel
                                <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="open" x-transition
                                class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg z-50">
                                <a href="/info" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fa-solid fa-location-dot"></i> Tabel Titik Bangunan
                                </a>
                                <a href="/infojalan" class="block px-4 py-2 text-sm text-[#fdcb2c] hover:bg-gray-100">
                                    <i class="fa-solid fa-road"></i> Tabel informasi jalan
                                </a>
                                <!-- <a href="/infobangunan" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fa-solid fa-draw-polygon"></i> Tabel Polygon Bangunan
                                </a> -->
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
                            class="absolute top-16 left-0 right-0 bg-[#083d62] border-t border-[#0a4a75] shadow-lg z-50">
                            <div class="px-4 py-2 space-y-2">
                                <a href="/" class="block text-gray-300 hover:text-white px-3 py-2 text-sm font-medium">
                                    <i class="fa-solid fa-house mr-2"></i>Beranda
                                </a>
                                <a href="/map" class="block text-gray-300 hover:text-white px-3 py-2 text-sm font-medium">
                                    <i class="fa-solid fa-map mr-2"></i>Peta
                                </a>
                                <div class="border-l-2 border-[#fdcb2c]">
                                    <div class="text-[#fdcb2c] px-3 py-2 text-sm font-medium">
                                        <i class="fa-solid fa-table mr-2"></i>Tabel
                                    </div>
                                    <a href="/info" class="block text-gray-300 hover:bg-[#0a4a75] px-6 py-2 text-sm">
                                        <i class="fa-solid fa-location-dot mr-2"></i>Titik Bangunan
                                    </a>
                                    <a href="/infojalan" class="block text-[#fdcb2c] hover:bg-[#0a4a75] px-6 py-2 text-sm">
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

        <!-- Main content -->
        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="px-4 py-6 sm:px-0">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">Daftar Informasi Jalan</h2>
                <!-- Form Pencarian -->
                <form method="GET" action="{{ url('/infojalan') }}" class="mb-6 flex items-center gap-2">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari nama jalan..."
                        class="px-4 py-2 border border-gray-300 rounded-lg w-full max-w-sm focus:ring-[#083d62] focus:border-[#083d62]">
                    <button type="submit" class="bg-[#083d62] text-white px-4 py-2 rounded-lg hover:bg-[#0a4a75]">
                        Cari
                    </button>
                </form>

                <div class="overflow-x-auto bg-white shadow rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No ↕️</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Jalan ↕️</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Panjang Jalan (meter) ↕️</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Material Jalan ↕️</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($roads as $index => $road)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $road->nama }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $road->pjg_jln_bulat }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $road->material }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada data jalan ditemukan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Custom Pagination -->
                    @if($roads->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        <div class="flex items-center justify-between">
                            {{-- Data count information --}}
                            <div class="text-sm text-gray-700">
                                Menampilkan {{ $roads->firstItem() }} sampai {{ $roads->lastItem() }} dari {{ $roads->total() }} data
                            </div>

                            {{-- Pagination navigation --}}
                            <nav class="flex items-center space-x-1" aria-label="Pagination">
                                {{-- Previous Page Link --}}
                                @if ($roads->onFirstPage())
                                <span class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-400 cursor-not-allowed">
                                    <i class="fas fa-chevron-left"></i>
                                </span>
                                @else
                                <a href="{{ $roads->previousPageUrl() }}"
                                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                                @endif

                                {{-- Pagination Elements --}}
                                @foreach ($roads->getUrlRange(1, $roads->lastPage()) as $page => $url)
                                @if ($page == $roads->currentPage())
                                <span class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-[#083d62] border border-[#083d62] rounded">
                                    {{ $page }}
                                </span>
                                @else
                                <a href="{{ $url }}"
                                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 hover:text-gray-900 rounded">
                                    {{ $page }}
                                </a>
                                @endif
                                @endforeach

                                {{-- Next Page Link --}}
                                @if ($roads->hasMorePages())
                                <a href="{{ $roads->nextPageUrl() }}"
                                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                                @else
                                <span class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-400 cursor-not-allowed">
                                    <i class="fas fa-chevron-right"></i>
                                </span>
                                @endif
                            </nav>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-gray-800 mt-12">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8 text-center text-gray-400">
                <p class="text-xs sm:text-base text-gray-400">
                    © Ellena Nurlaila sebagai syarat Proyek Akhir (PA) 2025, serta dibimbing oleh Ari Cahyono, S.Si., Msc.
                </p>
                <p class="text-xs sm:text-base text-gray-400">
                    Prodi Sistem Informasi Geografis
                </p>
                <p class="text-xs sm:text-base text-gray-400">
                    Departemen Teknologi Kebumian
                </p>
                <p class="text-xs sm:text-base text-gray-400">
                    Sekolah Vokasi
                </p>
                <p class="text-xs sm:text-base text-gray-400">
                    Universitas Gadjah Mada
                </p>
            </div>
        </footer>
    </div>
</body>

</html>