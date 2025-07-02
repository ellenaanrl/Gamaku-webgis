<!DOCTYPE html>

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
                        <img src="{{ asset('images/logo kuningg.png') }}" alt="Gamaku Logo" class="h-12 w-auto object-contain" />
                        @endif
                        <h1 class="text-2xl font-bold text-[#fdcb2c]">Gamaku WebGIS</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="/" class="text-gray-300 hover:text-white active:text-[#fdcb2c] px-3 py-2 text-sm font-medium">Beranda</a>
                        <a href="/map" class="text-gray-300 hover:text-white active:text-[#fdcb2c] px-3 py-2 text-sm font-medium">Peta</a>
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" @click.away="open = false"
                                class="text-[#fdcb2c] px-3 py-2 text-sm font-medium flex items-center focus:outline-none">
                                Tabel
                                <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="open" x-transition
                                class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg z-50">
                                <a href="/info" class="block px-4 py-2 text-sm text-[#fdcb2c] hover:bg-gray-100">Tabel Titik Bangunan</a>
                                <a href="/infojalan" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Tabel Polygon Jalan</a>
                                <a href="/infobangunan" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Tabel Polygon Bangunan</a>
                            </div>
                        </div>
                        <a href="/management" class="text-gray-300 hover:text-white active:text-[#fdcb2c] px-3 py-2 text-sm font-medium">Laporkan Kerusakan</a>
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
        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="px-4 py-6 sm:px-0">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-900">Daftar Titik Bangunan</h2>
                    <button id="refreshData" class="bg-[#083d62] text-white px-4 py-2 rounded-lg hover:bg-[#0a4a75] transition-colors">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Refresh Data
                    </button>
                </div>

                <div class="mb-6 flex flex-col sm:flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" id="searchInput" placeholder="Cari nama, unit, atau jenis bangunan..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#083d62] focus:border-transparent">
                    </div>
                    <div class="flex gap-2">
                        <select id="unitFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#083d62] focus:border-transparent">
                            <option value="">Semua Unit</option>
                        </select>
                        <select id="jenisFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#083d62] focus:border-transparent">
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
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200" id="bangunanTable">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" onclick="sortTable(0)">
                                        No
                                        <span class="ml-1">↕️</span>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" onclick="sortTable(1)">
                                        Nama
                                        <span class="ml-1">↕️</span>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" onclick="sortTable(2)">
                                        Unit
                                        <span class="ml-1">↕️</span>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" onclick="sortTable(3)">
                                        Jenis Bangunan
                                        <span class="ml-1">↕️</span>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Dokumentasi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="bangunanTableBody">
                                @forelse($buildings as $index => $building)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $index + 1 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $building->Nama }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $building->Unit }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $building->Jenis_Bang }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        @php
                                        $fotoPath = asset('storage/' . ltrim($building->Dokumentasi, 'storage/'));
                                        @endphp

                                        <button onclick="showDetails('{{ $fotoPath }}')" class="text-blue-600 hover:underline">
                                            Lihat Foto
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                        Tidak ada data yang ditemukan
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                        <div class="flex-1 flex justify-between sm:hidden">
                            <button id="prevPageMobile" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                Previous
                            </button>
                            <button id="nextPageMobile" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                Next
                            </button>
                        </div>
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
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

                <!-- Modal Detail -->
                <div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
                    <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full">
                        <h2 class="text-xl font-semibold mb-4 text-gray-800">Dokumentasi Bangunan</h2>
                        <img id="modalImage" src="" alt="Foto Dokumentasi" class="w-full rounded-md border border-gray-200 shadow-sm">
                        <button onclick="closeModal()" class="mt-4 bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                            Tutup
                        </button>
                    </div>
                </div>

                <!-- Empty State -->
                <div id="emptyState" class="bg-white shadow-sm rounded-lg p-8 text-center hidden">
                    <div class="text-gray-400 text-4xl mb-4">📍</div>
                    <p class="text-gray-600 mb-2">Tidak ada data yang ditemukan</p>
                    <p class="text-gray-500 text-sm">Coba ubah kriteria pencarian Anda</p>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-gray-800 mt-12">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
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
            const rows = Array.from(document.querySelectorAll('#bangunanTableBody tr'));
            const totalRecords = document.getElementById('totalRecords');
            const showingFrom = document.getElementById('showingFrom');
            const showingTo = document.getElementById('showingTo');
            const paginationButtons = document.getElementById('paginationButtons');
            const prevPageMobile = document.getElementById('prevPageMobile');
            const nextPageMobile = document.getElementById('nextPageMobile');
            const itemsPerPage = 10;
            let currentPage = 1;
            let filteredRows = [];

            // Populate filter dropdowns from table data
            const units = [...new Set(rows.map(row => row.cells[2]?.textContent.trim()).filter(Boolean))].sort();
            const jenis = [...new Set(rows.map(row => row.cells[3]?.textContent.trim()).filter(Boolean))].sort();

            // Clear existing options except the first one (Semua)
            while (unitFilter.options.length > 1) unitFilter.remove(1);
            while (jenisFilter.options.length > 1) jenisFilter.remove(1);

            // Add sorted options
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

                filteredRows = rows.filter(row => {
                    if (!row.cells || row.cells.length < 4) return false;

                    const unitVal = (row.cells[2]?.textContent || '').trim().toLowerCase();
                    const jenisVal = (row.cells[3]?.textContent || '').trim().toLowerCase();
                    const namaVal = (row.cells[1]?.textContent || '').trim().toLowerCase();

                    // Check if row matches all active filters
                    const matchUnit = !unit || unitVal === unit;
                    const matchJenis = !jenis || jenisVal === jenis;
                    const matchSearch = !search ||
                        namaVal.includes(search) ||
                        unitVal.includes(search) ||
                        jenisVal.includes(search);

                    return matchUnit && matchJenis && matchSearch;
                });

                // Reset to first page when filtering
                currentPage = 1;
                renderTable();
            }

            function renderTable() {
                // First hide all rows
                rows.forEach(row => row.style.display = 'none');

                const total = filteredRows.length;
                const totalPages = Math.max(1, Math.ceil(total / itemsPerPage));

                // Ensure current page is valid
                if (currentPage > totalPages) currentPage = totalPages;

                const startIdx = (currentPage - 1) * itemsPerPage;
                const endIdx = Math.min(startIdx + itemsPerPage, total);

                // Show only rows for current page
                filteredRows.slice(startIdx, endIdx).forEach(row => {
                    row.style.display = '';
                });

                // Update pagination info
                totalRecords.textContent = total;
                showingFrom.textContent = total === 0 ? 0 : startIdx + 1;
                showingTo.textContent = endIdx;

                // Update pagination buttons
                renderPaginationButtons(totalPages);

                // Toggle empty state
                document.getElementById('emptyState').style.display = total === 0 ? '' : 'none';
                document.getElementById('dataTable').style.display = total === 0 ? 'none' : '';

                // Update No. column to reflect current page
                filteredRows.forEach((row, idx) => {
                    if (row.cells[0]) {
                        row.cells[0].textContent = idx + 1;
                    }
                });
            }

            function renderPaginationButtons(totalPages) {
                paginationButtons.innerHTML = '';
                // Previous button
                const prevBtn = document.createElement('button');
                prevBtn.className = 'relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50';
                prevBtn.innerHTML = '<span class="sr-only">Previous</span><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>';
                prevBtn.disabled = currentPage === 1;
                prevBtn.onclick = () => {
                    currentPage--;
                    renderTable();
                };
                paginationButtons.appendChild(prevBtn);
                // Page numbers (show max 5)
                let start = Math.max(1, currentPage - 2);
                let end = Math.min(totalPages, start + 4);
                if (end - start < 4) start = Math.max(1, end - 4);
                for (let i = start; i <= end; i++) {
                    const btn = document.createElement('button');
                    btn.className = 'relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium ' + (i === currentPage ? 'z-10 bg-[#083d62] text-white' : 'bg-white text-gray-700 hover:bg-gray-50');
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
                    currentPage++;
                    renderTable();
                };
                paginationButtons.appendChild(nextBtn);
                // Mobile prev/next
                prevPageMobile.disabled = currentPage === 1;
                nextPageMobile.disabled = currentPage === totalPages;
            }

            // Mobile prev/next events
            prevPageMobile.onclick = function() {
                if (currentPage > 1) {
                    currentPage--;
                    renderTable();
                }
            };
            nextPageMobile.onclick = function() {
                if (currentPage < Math.ceil(filteredRows.length / itemsPerPage)) {
                    currentPage++;
                    renderTable();
                }
            };

            // Filter, search, and pagination events
            unitFilter.addEventListener('change', () => {
                currentPage = 1;
                filterAndSearchRows();
                renderTable();
            });
            jenisFilter.addEventListener('change', () => {
                currentPage = 1;
                filterAndSearchRows();
                renderTable();
            });
            searchInput.addEventListener('input', () => {
                currentPage = 1;
                filterAndSearchRows();
                renderTable();
            });

            // Refresh button functionality
            document.getElementById('refreshData').addEventListener('click', function() {
                location.reload();
            });

            // Initial call
            filterAndSearchRows();
            renderTable();
        });

        function showDetails(imagePath) {
            document.getElementById('modalImage').src = imagePath;
            const modal = document.getElementById('detailModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal() {
            const modal = document.getElementById('detailModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
</body>

</html>