<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Informasi - Gamaku WebGIS</title>
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

    <style>
        body {
            font-family: 'Gama Sans', sans-serif;
        }
        
        /* Custom scrollbar for mobile */
        .custom-scrollbar::-webkit-scrollbar {
            height: 6px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #083d62;
            border-radius: 3px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #0a4a75;
        }

        /* Mobile card styles */
        .mobile-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .mobile-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .mobile-card dt {
            font-weight: 500;
        }
        
        .mobile-card dd {
            word-wrap: break-word;
        }
    </style>
</head>

<body class="antialiased bg-gray-50">
    <div class="relative min-h-screen">
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
                                <a href="/info" class="block px-4 py-2 text-sm text-[#fdcb2c] hover:bg-gray-100">
                                    <i class="fa-solid fa-location-dot"></i> Tabel Titik Bangunan
                                </a>
                                <a href="/infojalan" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
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
                                    <a href="/info" class="block text-[#fdcb2c] hover:bg-[#0a4a75] px-6 py-2 text-sm">
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

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto py-4 sm:py-6 px-3 sm:px-6 lg:px-8">
            <div class="space-y-6">
                <!-- Header -->
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Daftar Titik Bangunan</h2>
                    <button id="refreshData" class="bg-[#083d62] text-white px-4 py-2 rounded-lg hover:bg-[#0a4a75] transition-colors inline-flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        <span class="hidden sm:inline">Refresh Data</span>
                        <span class="sm:hidden">Refresh</span>
                    </button>
                </div>

                <!-- Filters -->
                <div class="space-y-4">
                    <div class="w-full">
                        <input type="text" id="searchInput" placeholder="Cari nama, unit, atau jenis bangunan..." 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#083d62] focus:border-transparent text-base">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <select id="unitFilter" class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#083d62] focus:border-transparent text-base">
                            <option value="">Semua Unit</option>
                        </select>
                        <select id="jenisFilter" class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#083d62] focus:border-transparent text-base">
                            <option value="">Semua Jenis</option>
                        </select>
                    </div>
                </div>

                <!-- Loading State -->
                <div id="loadingState" class="bg-white shadow-sm rounded-lg p-8 text-center hidden">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-[#083d62] mb-4"></div>
                    <p class="text-gray-600">Memuat data...</p>
                </div>

                <!-- Error State -->
                <div id="errorState" class="bg-white shadow-sm rounded-lg p-8 text-center hidden">
                    <div class="text-red-400 text-4xl mb-4">⚠️</div>
                    <p class="text-red-600 mb-4">Terjadi kesalahan saat memuat data</p>
                    <button onclick="loadData()" class="bg-[#083d62] text-white px-4 py-2 rounded-lg hover:bg-[#0a4a75]">
                        Coba Lagi
                    </button>
                </div>

                <!-- Data Table -->
                <div id="dataTable" class="bg-white shadow-sm rounded-lg overflow-hidden">
                    <!-- Desktop Table -->
                    <div class="hidden md:block overflow-x-auto custom-scrollbar">
                        <table class="min-w-full divide-y divide-gray-200" id="bangunanTable">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" onclick="sortTable(0)">
                                        <span class="inline-flex items-center">
                                            No
                                            <span class="ml-1">↕️</span>
                                        </span>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" onclick="sortTable(1)">
                                        <span class="inline-flex items-center">
                                            Nama
                                            <span class="ml-1">↕️</span>
                                        </span>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" onclick="sortTable(2)">
                                        <span class="inline-flex items-center">
                                            Unit
                                            <span class="ml-1">↕️</span>
                                        </span>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" onclick="sortTable(3)">
                                        <span class="inline-flex items-center">
                                            Jenis Bangunan
                                            <span class="ml-1">↕️</span>
                                        </span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="bangunanTableBody">
                                @forelse($buildings as $index => $building)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $index + 1 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $building->nama }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $building->unit }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $building->jenis_bang }}</div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                        Tidak ada data yang ditemukan
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Card Layout -->
                    <div class="md:hidden space-y-4" id="mobileCardContainer">
                        @forelse($buildings as $index => $building)
                        <div class="mobile-card bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center space-x-2">
                                    <span class="inline-flex items-center justify-center w-6 h-6 bg-[#083d62] text-white text-xs font-medium rounded-full">
                                        {{ $index + 1 }}
                                    </span>
                                    <span class="text-xs text-gray-500 uppercase tracking-wide">Bangunan</span>
                                </div>
                            </div>
                            
                            <div class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 mb-1">Nama Bangunan</dt>
                                    <dd class="text-base font-semibold text-gray-900">{{ $building->nama }}</dd>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 mb-1">Unit</dt>
                                        <dd class="text-sm text-gray-900">{{ $building->unit }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 mb-1">Jenis</dt>
                                        <dd class="text-sm text-gray-900">{{ $building->jenis_bang }}</dd>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8">
                            <div class="text-gray-400 text-4xl mb-4">🏢</div>
                            <p class="text-gray-500">Tidak ada data bangunan yang ditemukan</p>
                        </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    <div class="bg-white px-4 py-3 border-t border-gray-200">
                        <!-- Mobile Pagination -->
                        <div class="sm:hidden">
                            <div class="flex justify-between items-center mb-3">
                                <p class="text-sm text-gray-700" id="paginationInfoMobile">
                                    <span id="showingFromMobile">1</span>-<span id="showingToMobile">10</span> dari <span id="totalRecordsMobile">0</span>
                                </p>
                            </div>
                            <div class="flex justify-between">
                                <button id="prevPageMobile" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <i class="fa-solid fa-chevron-left mr-1"></i> Sebelumnya
                                </button>
                                <button id="nextPageMobile" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                    Selanjutnya <i class="fa-solid fa-chevron-right ml-1"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Desktop Pagination -->
                        <div class="hidden sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700" id="paginationInfo">
                                    Menampilkan <span class="font-medium" id="showingFrom">1</span> sampai <span class="font-medium" id="showingTo">10</span> dari <span class="font-medium" id="totalRecords">0</span> data
                                </p>
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" id="paginationButtons">
                                    <!-- Pagination buttons will be generated here -->
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div id="emptyState" class="bg-white shadow-sm rounded-lg p-8 text-center hidden">
                    <i class="fa-solid fa-ban text-gray-400 text-4xl mb-4"></i>
                    <p class="text-gray-600 mb-2">Tidak ada data yang ditemukan</p>
                    <p class="text-gray-500 text-sm">Coba ubah kriteria pencarian Anda</p>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-gray-800 mt-12">
            <div class="max-w-7xl mx-auto py-8 sm:py-12 px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <p class="text-base text-gray-400">
                        © 2025 Gamaku WebGIS. All rights reserved.
                    </p>
                </div>
            </div>
        </footer>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const unitFilter = document.getElementById('unitFilter');
            const jenisFilter = document.getElementById('jenisFilter');
            const searchInput = document.getElementById('searchInput');
            
            // Get all rows from both desktop and mobile tables
            const desktopRows = Array.from(document.querySelectorAll('#bangunanTableBody tr'));
            const mobileCards = Array.from(document.querySelectorAll('.mobile-card'));
            
            const totalRecords = document.getElementById('totalRecords');
            const showingFrom = document.getElementById('showingFrom');
            const showingTo = document.getElementById('showingTo');
            const totalRecordsMobile = document.getElementById('totalRecordsMobile');
            const showingFromMobile = document.getElementById('showingFromMobile');
            const showingToMobile = document.getElementById('showingToMobile');
            const paginationButtons = document.getElementById('paginationButtons');
            const prevPageMobile = document.getElementById('prevPageMobile');
            const nextPageMobile = document.getElementById('nextPageMobile');
            const itemsPerPage = 10;
            let currentPage = 1;
            let filteredDesktopRows = [];
            let filteredMobileCards = [];

            // Get unique values for filters from desktop table
            const units = [...new Set(desktopRows.map(row => {
                const cells = row.querySelectorAll('td');
                return cells.length >= 3 ? cells[2].textContent.trim() : '';
            }).filter(Boolean))].sort();

            const jenis = [...new Set(desktopRows.map(row => {
                const cells = row.querySelectorAll('td');
                return cells.length >= 4 ? cells[3].textContent.trim() : '';
            }).filter(Boolean))].sort();

            // Populate filter dropdowns
            while (unitFilter.options.length > 1) unitFilter.remove(1);
            while (jenisFilter.options.length > 1) jenisFilter.remove(1);

            units.forEach(unit => {
                const option = document.createElement('option');
                option.value = unit;
                option.textContent = unit;
                unitFilter.appendChild(option);
            });

            jenis.forEach(j => {
                const option = document.createElement('option');
                option.value = j;
                option.textContent = j;
                jenisFilter.appendChild(option);
            });

            function filterAndSearchRows() {
                const unit = unitFilter.value.toLowerCase();
                const jenis = jenisFilter.value.toLowerCase();
                const search = searchInput.value.trim().toLowerCase();

                // Filter desktop rows
                filteredDesktopRows = desktopRows.filter(row => {
                    const cells = row.querySelectorAll('td');
                    if (cells.length < 4) return false;

                    const unitVal = cells[2].textContent.trim().toLowerCase();
                    const jenisVal = cells[3].textContent.trim().toLowerCase();
                    const namaVal = cells[1].textContent.trim().toLowerCase();

                    const matchUnit = !unit || unitVal === unit;
                    const matchJenis = !jenis || jenisVal === jenis;
                    const matchSearch = !search ||
                        namaVal.includes(search) ||
                        unitVal.includes(search) ||
                        jenisVal.includes(search);

                    return matchUnit && matchJenis && matchSearch;
                });

                // Filter mobile cards
                filteredMobileCards = mobileCards.filter(card => {
                    const namaElement = card.querySelector('dd');
                    const unitElement = card.querySelectorAll('dd')[1];
                    const jenisElement = card.querySelectorAll('dd')[2];
                    
                    if (!namaElement || !unitElement || !jenisElement) return false;

                    const namaVal = namaElement.textContent.trim().toLowerCase();
                    const unitVal = unitElement.textContent.trim().toLowerCase();
                    const jenisVal = jenisElement.textContent.trim().toLowerCase();

                    const matchUnit = !unit || unitVal === unit;
                    const matchJenis = !jenis || jenisVal === jenis;
                    const matchSearch = !search ||
                        namaVal.includes(search) ||
                        unitVal.includes(search) ||
                        jenisVal.includes(search);

                    return matchUnit && matchJenis && matchSearch;
                });

                currentPage = 1;
                renderTable();
            }

            function renderTable() {
                // Hide all rows and cards
                desktopRows.forEach(row => row.style.display = 'none');
                mobileCards.forEach(card => card.style.display = 'none');

                const total = Math.max(filteredDesktopRows.length, filteredMobileCards.length);
                const totalPages = Math.max(1, Math.ceil(total / itemsPerPage));

                if (currentPage > totalPages) currentPage = totalPages;

                const startIdx = (currentPage - 1) * itemsPerPage;
                const endIdx = Math.min(startIdx + itemsPerPage, total);

                // Show desktop rows for current page
                filteredDesktopRows.slice(startIdx, endIdx).forEach(row => {
                    row.style.display = '';
                });

                // Show mobile cards for current page
                filteredMobileCards.slice(startIdx, endIdx).forEach(card => {
                    card.style.display = '';
                });

                // Update pagination info
                const showingFromVal = total === 0 ? 0 : startIdx + 1;
                const showingToVal = endIdx;

                if (totalRecords) totalRecords.textContent = total;
                if (showingFrom) showingFrom.textContent = showingFromVal;
                if (showingTo) showingTo.textContent = showingToVal;
                if (totalRecordsMobile) totalRecordsMobile.textContent = total;
                if (showingFromMobile) showingFromMobile.textContent = showingFromVal;
                if (showingToMobile) showingToMobile.textContent = showingToVal;

                // Update pagination buttons
                renderPaginationButtons(totalPages);

                // Toggle empty state
                document.getElementById('emptyState').style.display = total === 0 ? 'block' : 'none';
                document.getElementById('dataTable').style.display = total === 0 ? 'none' : 'block';

                // Update desktop row numbers
                filteredDesktopRows.forEach((row, idx) => {
                    const firstCell = row.querySelector('td');
                    if (firstCell) {
                        firstCell.textContent = idx + 1;
                    }
                });

                // Update mobile card numbers
                filteredMobileCards.forEach((card, idx) => {
                    const numberElement = card.querySelector('.w-6.h-6');
                    if (numberElement) {
                        numberElement.textContent = idx + 1;
                    }
                });
            }

            function renderPaginationButtons(totalPages) {
                if (!paginationButtons) return;

                paginationButtons.innerHTML = '';

                // Previous button
                const prevBtn = document.createElement('button');
                prevBtn.className = 'relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50';
                prevBtn.innerHTML = '<span class="sr-only">Previous</span><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>';
                prevBtn.disabled = currentPage === 1;
                prevBtn.onclick = () => {
                    if (currentPage > 1) {
                        currentPage--;
                        renderTable();
                    }
                };
                paginationButtons.appendChild(prevBtn);

                // Page numbers
                let start = Math.max(1, currentPage - 2);
                let end = Math.min(totalPages, start + 4);
                if (end - start < 4) start = Math.max(1, end - 4);

                for (let i = start; i <= end; i++) {
                    const btn = document.createElement('button');
                    btn.className = 'relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium ' + 
                        (i === currentPage ? 'z-10 bg-[#083d62] text-white' : 'bg-white text-gray-700 hover:bg-gray-50');
                    btn.textContent = i;
                    btn.onclick = () => {
                        currentPage = i;
                        renderTable();
                    };
                    paginationButtons.appendChild(btn);
                }

                // Next button
                const nextBtn = document.createElement('button');
                nextBtn.className = 'relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50';
                nextBtn.innerHTML = '<span class="sr-only">Next</span><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>';
                nextBtn.disabled = currentPage === totalPages;
                nextBtn.onclick = () => {
                    if (currentPage < totalPages) {
                        currentPage++;
                        renderTable();
                    }
                };
                paginationButtons.appendChild(nextBtn);

                // Update mobile prev/next buttons
                if (prevPageMobile) {
                    prevPageMobile.disabled = currentPage === 1;
                }
                if (nextPageMobile) {
                    nextPageMobile.disabled = currentPage === totalPages;
                }
            }

            // Event listeners
            unitFilter.addEventListener('change', filterAndSearchRows);
            jenisFilter.addEventListener('change', filterAndSearchRows);
            searchInput.addEventListener('input', filterAndSearchRows);

            // Mobile pagination events
            if (prevPageMobile) {
                prevPageMobile.onclick = function() {
                    if (currentPage > 1) {
                        currentPage--;
                        renderTable();
                    }
                };
            }

            if (nextPageMobile) {
                nextPageMobile.onclick = function() {
                    const totalPages = Math.ceil(Math.max(filteredDesktopRows.length, filteredMobileCards.length) / itemsPerPage);
                    if (currentPage < totalPages) {
                        currentPage++;
                        renderTable();
                    }
                };
            }

            // Refresh button functionality
            document.getElementById('refreshData').addEventListener('click', function() {
                location.reload();
            });

            // Initialize
            filterAndSearchRows();
        });

        // Sorting function
        let sortDirection = {};
        
        function sortTable(columnIndex) {
            const table = document.getElementById('bangunanTable');
            const tbody = table.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr'));
            
            // Toggle sort direction
            sortDirection[columnIndex] = sortDirection[columnIndex] === 'asc' ? 'desc' : 'asc';
            
            rows.sort((a, b) => {
                const aVal = a.cells[columnIndex].textContent.trim();
                const bVal = b.cells[columnIndex].textContent.trim();
                
                // For number column (index 0)
                if (columnIndex === 0) {
                    return sortDirection[columnIndex] === 'asc' ? 
                        parseInt(aVal) - parseInt(bVal) : 
                        parseInt(bVal) - parseInt(aVal);
                }
                
                // For text columns
                return sortDirection[columnIndex] === 'asc' ? 
                    aVal.localeCompare(bVal) : 
                    bVal.localeCompare(aVal);
            });
            
            // Re-append sorted rows
            rows.forEach(row => tbody.appendChild(row));
        }

        // Touch/swipe support for mobile table
        let startX = 0;
        let startY = 0;
        
        document.addEventListener('touchstart', function(e) {
            startX = e.touches[0].clientX;
            startY = e.touches[0].clientY;
        });
        
        document.addEventListener('touchmove', function(e) {
            if (!startX || !startY) return;
            
            const diffX = startX - e.touches[0].clientX;
            const diffY = startY - e.touches[0].clientY;
            
            // Horizontal swipe detection for pagination
            if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > 50) {
                const totalPages = Math.ceil(document.querySelectorAll('#bangunanTableBodyMobile tr:not([style*="display: none"])').length / 10);
                
                if (diffX > 0 && currentPage < totalPages) {
                    // Swipe left - next page
                    currentPage++;
                    renderTable();
                } else if (diffX < 0 && currentPage > 1) {
                    // Swipe right - previous page
                    currentPage--;
                    renderTable();
                }
            }
            
            startX = 0;
            startY = 0;
        });

        // Responsive table handling
        function handleResize() {
            const isMobile = window.innerWidth < 768;
            const desktopTable = document.querySelector('.hidden.md\\:block');
            const mobileTable = document.querySelector('.md\\:hidden');
            
            if (isMobile) {
                if (desktopTable) desktopTable.style.display = 'none';
                if (mobileTable) mobileTable.style.display = 'block';
            } else {
                if (desktopTable) desktopTable.style.display = 'block';
                if (mobileTable) mobileTable.style.display = 'none';
            }
        }

        window.addEventListener('resize', handleResize);
        handleResize(); // Initial call
    </script>
</body>
</html>