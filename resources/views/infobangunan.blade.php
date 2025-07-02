<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Informasi Bangunan - Gamaku WebGIS</title>

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
    <style>
        body {
            font-family: 'Gama Sans', sans-serif;
        }
    </style>
</head>

<body class="antialiased bg-gray-50">
    <div class="relative min-h-screen">
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
                        <a href="/map" class="text-gray-300 hover:text-white active:text-[#fdcb2c] px-3 py-2 text-sm font-medium">Peta</a>
                        <a href="/info" class="text-gray-300 hover:text-white active:text-[#fdcb2c] px-3 py-2 text-sm font-medium">Titik</a>
                        <a href="/infobangunan" class="text-[#fdcb2c] px-3 py-2 text-sm font-medium">Bangunan</a>
                        <a href="/management" class="text-gray-300 hover:text-white px-3 py-2 text-sm font-medium">Laporkan Kerusakan</a>
                        @auth
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                                    Logout
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="bg-white text-[#083d62] hover:bg-gray-100 px-4 py-2 rounded-md text-sm font-medium">
                                Masuk/Daftar
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="px-4 py-6 sm:px-0">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">Daftar Bangunan Polygon</h2>

                <div class="overflow-x-auto bg-white shadow rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis Bangunan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($buildings as $index => $building)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $building->Nama ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $building->Unit ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $building->Jenis_Bang ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <button onclick='alert(@json($building))' class="text-[#083d62] hover:underline">
                                        Detail
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada data ditemukan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-gray-800 mt-12">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8 text-center text-gray-400">
                © 2025 Gamaku WebGIS. All rights reserved.
            </div>
        </footer>
    </div>
</body>
</html>
