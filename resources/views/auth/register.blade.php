<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - Gamaku WebGIS</title>

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

<body class="antialiased bg-gray-50 font-sans">
    <div class="min-h-screen">
        <!-- Navigation -->
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

                    <div class="hidden lg:flex items-center space-x-4">
                        <a href="/" class="text-gray-300 hover:text-white active:text-[#fdcb2c] px-3 py-2 text-sm font-medium whitespace-nowrap">
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
                                <a href="/" class="block text-gray-300 hover:text-white px-3 py-2 text-sm font-medium">
                                    <i class="fa-solid fa-house mr-2"></i>Beranda
                                </a>
                                <a href="/map" class="block text-gray-300 hover:text-white px-3 py-2 text-sm font-medium">
                                    <i class="fa-solid fa-map mr-2"></i>Peta
                                </a>
                                <div class="border-l-2 border-[#fdcb2c] pl-2">
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Register Section -->
        <div class="flex min-h-[calc(100vh-4rem)] bg-cover bg-center" style="background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('/UGM.jpeg')">
            <div class="flex-1 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
                <div class="sm:mx-auto sm:w-full sm:max-w-md">
                    <h2 class="text-center text-3xl font-bold tracking-tight text-white">Daftar Akun Baru</h2>
                    <p class="mt-2 text-center text-sm text-gray-200">
                        Atau
                        <a href="{{ route('login') }}" class="font-medium text-[#fdcb2c] hover:text-yellow-400">masuk ke akun</a>
                    </p>
                </div>

                <div class="mt-8 mx-4 sm:mx-auto sm:w-full sm:max-w-md">
                    <div class="backdrop-blur-md bg-white/90 py-6 px-4 sm:px-10 shadow-lg rounded-lg border border-white/20">
                        <form class="space-y-6" method="POST" action="{{ route('register') }}">
                            @csrf
                            @if ($errors->any())
                            <div class="bg-red-500/10 text-red-400 p-4 rounded-md border border-red-500/20">
                                <ul class="list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-800">Nama Lengkap</label>
                                <input id="name" name="name" type="text" :value="old('name')" required autofocus autocomplete="name"
                                    class="block w-full appearance-none rounded-md bg-white/50 border border-gray-300/50 px-3 py-2 
                                    placeholder-gray-400 shadow-sm focus:border-[#083d62] focus:outline-none focus:ring-[#083d62] sm:text-sm">
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-800">Email</label>
                                <input id="email" name="email" type="email" :value="old('email')" required autocomplete="username"
                                    class="block w-full appearance-none rounded-md bg-white/50 border border-gray-300/50 px-3 py-2 
                                    placeholder-gray-400 shadow-sm focus:border-[#083d62] focus:outline-none focus:ring-[#083d62] sm:text-sm">
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-800">Password</label>
                                <input id="password" name="password" type="password" required autocomplete="new-password"
                                    class="block w-full appearance-none rounded-md bg-white/50 border border-gray-300/50 px-3 py-2 
                                    placeholder-gray-400 shadow-sm focus:border-[#083d62] focus:outline-none focus:ring-[#083d62] sm:text-sm">
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-800">Konfirmasi Password</label>
                                <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                                    class="block w-full appearance-none rounded-md bg-white/50 border border-gray-300/50 px-3 py-2 
                                    placeholder-gray-400 shadow-sm focus:border-[#083d62] focus:outline-none focus:ring-[#083d62] sm:text-sm">
                            </div>

                            <div>
                                <button type="submit"
                                    class="flex w-full justify-center rounded-md border border-transparent bg-[#083d62] py-3 px-4 
                                    text-sm font-medium text-white shadow-sm hover:bg-[#083d62]/90 focus:outline-none focus:ring-2 
                                    focus:ring-[#fdcb2c] focus:ring-offset-2 transition-all duration-200">
                                    Daftar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-gray-800">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="text-center">
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
                </div>
            </div>
        </footer>
    </div>
</body>

</html>