<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Damage Reports - Admin Dashboard</title>
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

        /* Date filter styles */
        input[type="date"] {
            min-height: 38px;
        }

        @media (max-width: 640px) {
            .flex-col.sm\:flex-row {
                gap: 1rem;
            }
        }
    </style>
</head>

<body class="bg-gray-50 flex flex-col min-h-screen relative">
    <nav class="bg-[#083d62] shadow-sm sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-2 min-w-0 flex-shrink-0">
                    <img src="{{ asset('images/logo.png') }}" alt="Gamaku Logo" class="h-8 sm:h-12 w-auto object-contain flex-shrink-0" />
                    <h1 class="text-lg sm:text-2xl font-bold text-[#fdcb2c] truncate">Gamaku</h1>
                </div>

                <!-- Menu desktop -->
                <div class="hidden lg:flex items-center space-x-4">
                    <a href="{{ route('admin.map') }}" class="text-gray-300 hover:text-white px-3 py-2 text-sm font-medium"><i class="fa-solid fa-map"></i> Peta Data Spasial</a>
                    <a href="{{ route('admin.reportmap') }}" class="text-gray-300 hover:text-white px-3 py-2 text-sm font-medium"><i class="fa-solid fa-map-location-dot"></i> Peta Lokasi Kerusakan</a>
                    <a href="/admin/reports" class="block text-[#fdcb2c] hover:bg-[#0a4a75] px-3 py-2 text-sm font-medium"><i class="fa-solid fa-clipboard-list"></i> Laporan Kerusakan</a>
                    <div class="flex items-center bg-[#0a4a75] rounded-lg px-3 py-2">
                        <i class="fas fa-user-shield text-white mr-2"></i>
                        <span class="text-white text-sm font-medium">{{ Auth::user()->name }}</span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="bg-red-600 text-white hover:bg-red-700 px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fa-solid fa-right-from-bracket"></i> Logout
                        </button>
                    </form>
                </div>

                <!-- Hamburger button (mobile) -->
                <div class="lg:hidden" x-data="{ mobileOpen: false }">
                    <button @click="mobileOpen = !mobileOpen" class="text-gray-300 hover:text-white p-2">
                        <svg x-show="!mobileOpen" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg x-show="mobileOpen" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <!-- Mobile menu -->
                    <div x-show="mobileOpen" x-transition @click.away="mobileOpen = false"
                        class="absolute top-16 left-0 right-0 bg-[#083d62] border-t border-[#0a4a75] shadow-lg z-50 mobile-menu">
                        <div class="px-4 py-2 space-y-2">
                            <a href="{{ route('admin.map') }}" class="block text-gray-300 hover:text-white px-3 py-2 text-sm font-medium">
                                <i class="fa-solid fa-map mr-2"></i>Peta Data Spasial
                            </a>
                            <a href="{{ route('admin.reportmap') }}" class="block text-gray-300 hover:text-white px-3 py-2 text-sm font-medium">
                                <i class="fa-solid fa-map-location-dot mr-2"></i>Peta Lokasi Kerusakan
                            </a>
                            <a href="/admin/reports" class="block text-[#fdcb2c] hover:bg-[#0a4a75] px-3 py-2 text-sm font-medium">
                                <i class="fa-solid fa-clipboard-list mr-2"></i>Laporan Kerusakan
                            </a>

                            <div class="border-t border-[#0a4a75] pt-2">
                                <div class="flex items-center text-white px-3 py-2 text-sm">
                                    <i class="fa-solid fa-user-shield mr-2"></i>{{ Auth::user()->name }}
                                </div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left bg-red-600 text-white hover:bg-red-700 px-3 py-2 text-sm font-medium rounded">
                                        <i class="fa-solid fa-right-from-bracket mr-2"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        @if(session('status_updated'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            {{ session('status_updated') }}
        </div>
        @endif

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h2 class="text-lg font-medium text-gray-900"><i class="fa-solid fa-clipboard-list"></i> Laporan Kerusakan</h2>
                <p class="mt-1 text-sm text-gray-500">Daftar laporan kerusakan yang telah dikirim oleh pengguna</p>

                <!-- Add category filter -->
                <div class="mt-4 flex-1">
                    <label for="categoryFilter" class="block text-sm font-medium text-yellow-500">Kategori Kerusakan</label>
                    <select id="categoryFilter" name="categoryFilter"
                        class="mt-1 block w-full rounded-md border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="all">Semua Kategori</option>
                        @php
                        $categories = $reports->pluck('category')->unique()->sort();
                        @endphp
                        @foreach($categories as $category)
                        <option value="{{ $category }}">{{ $category }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mt-4 flex-1">
                    <label for="subcategoryFilter" class="block text-sm font-medium text-yellow-500">Sub Kategori</label>
                    <select id="subcategoryFilter" name="subcategoryFilter"
                        class="mt-1 block w-full rounded-md border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="all">Semua Sub Kategori</option>
                    </select>
                </div>
                <!-- Add impact filter -->
                <div class="mt-4 flex-1">
                    <label for="impactFilter" class="block text-sm font-medium text-yellow-500">Dampak Kerusakan</label>
                    <select id="impactFilter" name="impactFilter"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="all">Semua Dampak</option>
                        @php
                        $impacts = collect($reports)->pluck('impact')->flatten()->unique()->sort();
                        @endphp
                        @foreach($impacts as $impact)
                        <option value="{{ $impact }}">{{ $impact }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <!-- Add date filter section -->
        <div class="mt-4 flex flex-col sm:flex-row gap-4 items-end">
            <div class="flex-1">
                <label for="startDate" class="block text-sm font-medium text-yellow-500">Dari Tanggal</label>
                <input type="date" id="startDate" name="startDate"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div class="flex-1">
                <label for="endDate" class="block text-sm font-medium text-yellow-500">Sampai Tanggal</label>
                <input type="date" id="endDate" name="endDate"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div class="flex gap-2">
                <button onclick="filterByDate()"
                    class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 text-sm">
                    <i class="fa-solid fa-filter"></i> Filter
                </button>
                <button onclick="resetFilter()"
                    class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 text-sm">
                    <i class="fa-solid fa-rotate"></i> Reset
                </button>
            </div>
        </div>
        </div>

        <!-- Desktop Table View (hidden on mobile) -->
        <div class="hidden md:block overflow-x-auto mt-4">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-3 text-left text-xs font-medium text-yellow-500 uppercase tracking-wider w-32">
                            <i class="fa-solid fa-user"></i> Pelapor
                        </th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-yellow-500 uppercase tracking-wider w-32">
                            <i class="fa-solid fa-list"></i> Kategori
                        </th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-yellow-500 uppercase tracking-wider">
                            <i class="fa-solid fa-list"></i> Dampak
                        </th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-yellow-500 uppercase tracking-wider">
                            <i class="fa-solid fa-location-dot"></i> Lokasi
                        </th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-yellow-500 uppercase tracking-wider">
                            <i class="fa-solid fa-calendar-days"></i> Tanggal
                        </th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-yellow-500 uppercase tracking-wider">
                            <i class="fa-solid fa-list-check"></i> Status
                        </th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-yellow-500 uppercase tracking-wider">
                            <i class="fa-solid fa-circle-info"></i> Info
                        </th>
                        <th class="px-3 py-3 text-right text-xs font-medium text-yellow-500 uppercase tracking-wider w-24">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($reports as $report)
                    <tr data-report-id="{{ $report->id }}">
                        <td class="px-3 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $report->reporter_name }}</div>
                            <div class="text-sm font-medium text-gray-900">{{ $report->department }}</div>
                            <div class="text-sm font-medium text-gray-900">{{ $report->phone }}</div>
                        </td>
                        <td class="px-3 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $report->category }}</div>
                            <div class="text-sm text-gray-500">{{ $report->subcategory }}</div>
                        </td>
                        <td class="px-3 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $report->impact }}</div>
                        </td>
                        <td class="px-3 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $report->lokasi }}</div>
                        </td>
                        <td class="px-3 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $report->created_at->format('d-m-Y H:i') }}</div>
                        </td>
                        <td class="px-3 py-4 whitespace-nowrap">
                            <form action="{{ route('admin.reports.update-status', $report->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <select name="status"
                                    onchange="this.form.submit()"
                                    class="text-sm rounded-full px-3 py-1 
                                                       @if($report->status === 'pending') bg-yellow-100 text-yellow-800
                                                       @elseif($report->status === 'in_progress') bg-blue-100 text-blue-800
                                                       @elseif($report->status === 'completed') bg-green-100 text-green-800
                                                       @else bg-red-100 text-red-800 @endif">
                                    <option value="pending" {{ $report->status === 'pending' ? 'selected' : '' }}>Menunggu review</option>
                                    <option value="in_progress" {{ $report->status === 'in_progress' ? 'selected' : '' }}>Sedang ditangani</option>
                                    <option value="completed" {{ $report->status === 'completed' ? 'selected' : '' }}>Selesai</option>
                                    <option value="rejected" {{ $report->status === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                                </select>
                            </form>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <button class="text-indigo-600 hover:text-indigo-900"
                                onclick="showDetails(this)"
                                data-reporter_name="{{ $report->reporter_name }}"
                                data-department="{{ $report->department }}"
                                data-phone="{{ $report->phone }}"
                                data-email="{{ $report->email }}"
                                data-category="{{ $report->category }}"
                                data-subcategory="{{ $report->subcategory }}"
                                data-impact="{{ $report->impact }}"
                                data-floor="{{ $report->floor }}"
                                data-description="{{ $report->description }}"
                                data-latitude="{{ $report->latitude }}"
                                data-longitude="{{ $report->longitude }}"
                                data-lokasi="{{ $report->lokasi }}"
                                data-created_at="{{ $report->created_at->format('d-m-Y H:i') }}"
                                data-status="{{ $report->status }}">
                                View Details
                            </button>
                            @if($report->photo_path)
                            <a href="{{ asset('storage/' . $report->photo_path) }}" target="_blank"
                                class="ml-4 text-indigo-600 hover:text-indigo-900">
                                <i class="fa-solid fa-image"></i>
                            </a>
                            @endif
                        </td>
                        <td class="px-3 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <!-- ...existing action buttons... -->
                            <button
                                onclick="confirmDelete('{{ $report->id }}')"
                                class="text-red-600 hover:text-red-900 ml-2">
                                <i class="fas fa-trash"></i>
                            </button>

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View (visible only on mobile) -->
        <div class="md:hidden divide-y divide-gray-200">
            @foreach($reports as $report)
            <div class="p-4 space-y-3">
                <!-- Header with reporter info -->
                <div class="flex justify-between items-start">
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center space-x-2 mb-1">
                            <i class="fa-solid fa-user text-yellow-500 text-sm"></i>
                            <h3 class="text-sm font-medium text-gray-900 truncate">{{ $report->reporter_name }}</h3>
                        </div>
                        <div class="text-xs text-gray-500 space-y-1">
                            <div>{{ $report->department }}</div>
                            <div>{{ $report->phone }}</div>
                        </div>
                    </div>
                    <!-- Status badge -->
                    <div class="ml-2">
                        <form action="{{ route('admin.reports.update-status', $report->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <select name="status"
                                onchange="this.form.submit()"
                                class="text-xs rounded-full px-2 py-1 border-0 
                                                   @if($report->status === 'pending') bg-yellow-100 text-yellow-800
                                                   @elseif($report->status === 'in_progress') bg-blue-100 text-blue-800
                                                   @elseif($report->status === 'completed') bg-green-100 text-green-800
                                                   @else bg-red-100 text-red-800 @endif">
                                <option value="pending" {{ $report->status === 'pending' ? 'selected' : '' }}>Menunggu review</option>
                                <option value="in_progress" {{ $report->status === 'in_progress' ? 'selected' : '' }}>Sedang ditangani</option>
                                <option value="completed" {{ $report->status === 'completed' ? 'selected' : '' }}>Selesai</option>
                                <option value="rejected" {{ $report->status === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </form>
                    </div>
                </div>

                <!-- Category and location info -->
                <div class="grid grid-cols-2 gap-3 text-xs">
                    <div>
                        <div class="flex items-center space-x-1 text-gray-500 mb-1">
                            <i class="fa-solid fa-list"></i>
                            <span class="font-medium">Kategori</span>
                        </div>
                        <div class="text-gray-900">{{ $report->category }}</div>
                        <div class="text-gray-500">{{ $report->subcategory }}</div>
                        <div class="text-gray-500">{{ $report->impact }}</div>
                    </div>
                    <div>
                        <div class="flex items-center space-x-1 text-gray-500 mb-1">
                            <i class="fa-solid fa-location-dot"></i>
                            <span class="font-medium">Lokasi</span>
                        </div>
                        <div class="text-gray-900">{{ $report->lokasi }}</div>
                    </div>
                </div>

                <!-- Date -->
                <div class="text-xs">
                    <div class="flex items-center space-x-1 text-gray-500 mb-1">
                        <i class="fa-solid fa-calendar-days"></i>
                        <span class="font-medium">Tanggal</span>
                    </div>
                    <div class="text-gray-900">{{ $report->created_at->format('d-m-Y H:i') }}</div>
                </div>

                <!-- Action buttons -->
                <div class="flex justify-between items-center pt-2 border-t border-gray-100">
                    <button class="flex items-center space-x-1 text-indigo-600 hover:text-indigo-900 text-sm"
                        onclick="showDetails(this)"
                        data-reporter_name="{{ $report->reporter_name }}"
                        data-department="{{ $report->department }}"
                        data-phone="{{ $report->phone }}"
                        data-email="{{ $report->email }}"
                        data-category="{{ $report->category }}"
                        data-subcategory="{{ $report->subcategory }}"
                        data-impact="{{ $report->impact }}"
                        data-floor="{{ $report->floor }}"
                        data-description="{{ $report->description }}"
                        data-latitude="{{ $report->latitude }}"
                        data-longitude="{{ $report->longitude }}"
                        data-lokasi="{{ $report->lokasi }}"
                        data-created_at="{{ $report->created_at->format('d-m-Y H:i') }}"
                        data-status="{{ $report->status }}">
                        <i class="fa-solid fa-eye"></i>
                        <span>Detail</span>
                    </button>

                    @if($report->photo_path)
                    <a href="{{ Storage::url($report->photo_path) }}"
                        target="_blank"
                        class="flex items-center space-x-1 text-indigo-600 hover:text-indigo-900 text-sm">
                        <i class="fa-solid fa-image"></i>
                        <span>Foto</span>
                    </a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        </div>
    </main>

    <!-- Modal for viewing report details -->
    <div id="reportModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-4 mx-auto p-4 border w-11/12 max-w-2xl shadow-lg rounded-md bg-white my-8">
            <div class="mt-3">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modalTitle">Detail Laporan Kerusakan</h3>
                <div class="mt-4" id="modalContent">
                    <div class="space-y-4 text-sm">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <span class="font-semibold text-gray-700">Pelapor:</span>
                                <div class="mt-1 text-gray-900" id="modalReporterName"></div>
                                <div class="text-xs text-gray-500" id="modalDepartment"></div>
                                <div class="text-xs text-gray-500" id="modalPhone"></div>
                                <div class="text-xs text-gray-500" id="modalEmail"></div>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-700">Kategori:</span>
                                <div class="mt-1 text-gray-900" id="modalCategory"></div>
                                <div class="text-xs text-gray-500" id="modalSubcategory"></div>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-700">Dampak Yang Ditimbulkan:</span>
                                <div class="mt-1 text-gray-900" id="modalImpact"></div>
                            </div>
                        </div>

                        <div>
                            <span class="font-semibold text-gray-700">Lantai:</span>
                            <div class="mt-1 text-gray-900" id="modalfloor"></div>
                        </div>

                        <div>
                            <span class="font-semibold text-gray-700">Deskripsi kerusakan:</span>
                            <div class="mt-1 text-gray-900" id="modalDescription"></div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <span class="font-semibold text-gray-700">Lokasi:</span>
                                <div class="mt-1">
                                    <a href="#" id="modalLocation" target="_blank" class="text-indigo-600 hover:text-indigo-900">
                                        View on Map
                                    </a>
                                </div>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-700">Tanggal Laporan:</span>
                                <div class="mt-1 text-gray-900" id="modalCreatedAt"></div>
                            </div>
                        </div>

                        <div>
                            <span class="font-semibold text-gray-700">Status:</span>
                            <span class="ml-2 text-xs rounded-full px-2 py-1" id="modalStatus"></span>
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <button onclick="hideModal()"
                        class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add this before closing body tag -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Konfirmasi Hapus</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        Apakah Anda yakin ingin menghapus laporan ini?
                    </p>
                </div>
                <div class="items-center px-4 py-3">
                    <button id="deleteConfirmButton"
                        class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                        Hapus
                    </button>
                    <button onclick="closeDeleteModal()"
                        class="ml-2 px-4 py-2 bg-gray-100 text-gray-700 text-base font-medium rounded-md shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showDetails(btn) {
            document.getElementById('modalReporterName').textContent = btn.getAttribute('data-reporter_name');
            document.getElementById('modalDepartment').textContent = btn.getAttribute('data-department');
            document.getElementById('modalPhone').textContent = btn.getAttribute('data-phone');
            document.getElementById('modalEmail').textContent = btn.getAttribute('data-email');
            document.getElementById('modalCategory').textContent = btn.getAttribute('data-category');
            document.getElementById('modalSubcategory').textContent = btn.getAttribute('data-subcategory');
            document.getElementById('modalImpact').textContent = btn.getAttribute('data-impact');
            document.getElementById('modalfloor').textContent = btn.getAttribute('data-floor');
            document.getElementById('modalDescription').textContent = btn.getAttribute('data-description');
            document.getElementById('modalCreatedAt').textContent = btn.getAttribute('data-created_at');

            // Status badge coloring
            var status = btn.getAttribute('data-status');
            var statusSpan = document.getElementById('modalStatus');
            statusSpan.textContent = status.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
            statusSpan.className = 'ml-2 text-xs rounded-full px-2 py-1 ' +
                (status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                    status === 'in_progress' ? 'bg-blue-100 text-blue-800' :
                    status === 'completed' ? 'bg-green-100 text-green-800' :
                    'bg-red-100 text-red-800');

            // Location link
            var lat = btn.getAttribute('data-latitude');
            var lng = btn.getAttribute('data-longitude');
            var locA = document.getElementById('modalLocation');
            locA.href = 'https://www.google.com/maps?q=' + lat + ',' + lng;
            locA.textContent = 'View on Map';

            document.getElementById('reportModal').classList.remove('hidden');
        }

        function hideModal() {
            document.getElementById('reportModal').classList.add('hidden');
        }

        function filterByDate() {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;

            if (!startDate || !endDate) {
                alert('Please select both start and end dates');
                return;
            }

            // Convert dates to timestamps for comparison
            const start = new Date(startDate).getTime();
            const end = new Date(endDate).getTime() + (24 * 60 * 60 * 1000 - 1); // Include end date fully

            // Get all table rows
            const rows = document.querySelectorAll('tbody tr');
            const mobileCards = document.querySelectorAll('.md\\:hidden > div');

            // Filter desktop view
            rows.forEach(row => {
                const dateStr = row.querySelector('td:nth-child(4)').textContent.trim();
                const rowDate = new Date(dateStr.split(' ')[0].split('-').reverse().join('-')).getTime();
                row.style.display = (rowDate >= start && rowDate <= end) ? '' : 'none';
            });

            // Filter mobile view
            mobileCards.forEach(card => {
                const dateStr = card.querySelector('.text-gray-900:last-child').textContent.trim();
                const cardDate = new Date(dateStr.split(' ')[0].split('-').reverse().join('-')).getTime();
                card.style.display = (cardDate >= start && cardDate <= end) ? '' : 'none';
            });

            // Update summary
            updateFilterSummary(startDate, endDate);
        }

        function resetFilter() {
            document.getElementById('startDate').value = '';
            document.getElementById('endDate').value = '';

            // Show all rows
            const rows = document.querySelectorAll('tbody tr');
            const mobileCards = document.querySelectorAll('.md\\:hidden > div');

            rows.forEach(row => row.style.display = '');
            mobileCards.forEach(card => card.style.display = '');

            // Remove summary if exists
            const summary = document.getElementById('filterSummary');
            if (summary) summary.remove();
        }

        function updateFilterSummary(startDate, endDate) {
            // Remove existing summary if any
            const existingSummary = document.getElementById('filterSummary');
            if (existingSummary) existingSummary.remove();

            // Count visible rows
            const visibleRows = document.querySelectorAll('tbody tr:not([style*="display: none"])').length;

            // Create summary element
            const summary = document.createElement('div');
            summary.id = 'filterSummary';
            summary.className = 'mt-4 p-2 bg-blue-50 text-blue-700 rounded-md text-sm';
            summary.innerHTML = `
            <i class="fa-solid fa-info-circle"></i>
            Menampilkan ${visibleRows} laporan dari ${startDate} sampai ${endDate}
        `;

            // Insert after the filter controls
            const filterControls = document.querySelector('.mt-4.flex');
            filterControls.parentNode.insertBefore(summary, filterControls.nextSibling);
        }

        function filterReports() {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            const selectedCategory = document.getElementById('categoryFilter').value;

            const start = startDate ? new Date(startDate).getTime() : null;
            const end = endDate ? new Date(endDate).getTime() + (24 * 60 * 60 * 1000 - 1) : null;

            // Get all table rows and mobile cards
            const rows = document.querySelectorAll('tbody tr');
            const mobileCards = document.querySelectorAll('.md\\:hidden > div');

            let visibleCount = 0;

            // Filter desktop view
            rows.forEach(row => {
                const dateStr = row.querySelector('td:nth-child(4)').textContent.trim();
                const rowDate = new Date(dateStr.split(' ')[0].split('-').reverse().join('-')).getTime();
                const rowCategory = row.querySelector('td:nth-child(2)').textContent.trim().split('\n')[0];

                const dateMatch = (!start || !end) ? true : (rowDate >= start && rowDate <= end);
                const categoryMatch = selectedCategory === 'all' ? true : rowCategory === selectedCategory;

                const shouldShow = dateMatch && categoryMatch;
                row.style.display = shouldShow ? '' : 'none';
                if (shouldShow) visibleCount++;
            });

            // Filter mobile view
            mobileCards.forEach(card => {
                const dateStr = card.querySelector('.text-gray-900:last-child').textContent.trim();
                const cardDate = new Date(dateStr.split(' ')[0].split('-').reverse().join('-')).getTime();
                const cardCategory = card.querySelector('.text-gray-900').textContent.trim();

                const dateMatch = (!start || !end) ? true : (cardDate >= start && cardDate <= end);
                const categoryMatch = selectedCategory === 'all' ? true : cardCategory === selectedCategory;

                card.style.display = (dateMatch && categoryMatch) ? '' : 'none';
            });

            updateFilterSummary(startDate, endDate, selectedCategory, visibleCount);
        }

        // Update the existing filterByDate function
        function filterByDate() {
            filterReports();
        }

        // Update the existing resetFilter function
        function resetFilter() {
            document.getElementById('startDate').value = '';
            document.getElementById('endDate').value = '';
            document.getElementById('categoryFilter').value = 'all';
            document.getElementById('subcategoryFilter').value = 'all';

            const rows = document.querySelectorAll('tbody tr');
            const mobileCards = document.querySelectorAll('.md\\:hidden > div');

            rows.forEach(row => row.style.display = '');
            mobileCards.forEach(card => card.style.display = '');

            updateSubcategoryOptions();

            const summary = document.getElementById('filterSummary');
            if (summary) summary.remove();
        }

        // Update the summary function to include category information
        function updateFilterSummary(startDate, endDate, category, visibleCount) {
            const existingSummary = document.getElementById('filterSummary');
            if (existingSummary) existingSummary.remove();

            const summary = document.createElement('div');
            summary.id = 'filterSummary';
            summary.className = 'mt-4 p-2 bg-blue-50 text-blue-700 rounded-md text-sm';

            let summaryText = `<i class="fa-solid fa-info-circle"></i> Menampilkan ${visibleCount} laporan`;

            if (category !== 'all') {
                summaryText += ` dengan kategori "${category}"`;
            }

            if (startDate && endDate) {
                summaryText += ` dari ${startDate} sampai ${endDate}`;
            }

            summary.innerHTML = summaryText;

            const filterControls = document.querySelector('.mt-4.flex');
            filterControls.parentNode.insertBefore(summary, filterControls.nextSibling);
        }

        // Add event listener for category filter
        document.getElementById('categoryFilter').addEventListener('change', filterReports);


        // Add this function to update subcategory options based on selected category
        function updateSubcategoryOptions() {
            const selectedCategory = document.getElementById('categoryFilter').value;
            const subcategoryFilter = document.getElementById('subcategoryFilter');

            // Clear existing options except "All"
            while (subcategoryFilter.options.length > 1) {
                subcategoryFilter.remove(1);
            }

            if (selectedCategory === 'all') {
                return;
            }

            // Get all subcategories for selected category
            const rows = document.querySelectorAll('tbody tr');
            const subcategories = new Set();

            rows.forEach(row => {
                const category = row.querySelector('td:nth-child(2)').textContent.trim().split('\n')[0];
                if (category === selectedCategory) {
                    const subcategory = row.querySelector('td:nth-child(2)').textContent.trim().split('\n')[1];
                    if (subcategory) {
                        subcategories.add(subcategory);
                    }
                }
            });

            // Add subcategory options
            Array.from(subcategories).sort().forEach(subcategory => {
                const option = document.createElement('option');
                option.value = subcategory;
                option.textContent = subcategory;
                subcategoryFilter.appendChild(option);
            });
        }

        // Modify the existing filterReports function
        function filterReports() {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            const selectedCategory = document.getElementById('categoryFilter').value;
            const selectedSubcategory = document.getElementById('subcategoryFilter').value;

            const start = startDate ? new Date(startDate).getTime() : null;
            const end = endDate ? new Date(endDate).getTime() + (24 * 60 * 60 * 1000 - 1) : null;

            const rows = document.querySelectorAll('tbody tr');
            const mobileCards = document.querySelectorAll('.md\\:hidden > div');

            let visibleCount = 0;

            // Filter desktop view
            rows.forEach(row => {
                const dateStr = row.querySelector('td:nth-child(4)').textContent.trim();
                const rowDate = new Date(dateStr.split(' ')[0].split('-').reverse().join('-')).getTime();
                const rowCategory = row.querySelector('td:nth-child(2)').textContent.trim().split('\n')[0];
                const rowSubcategory = row.querySelector('td:nth-child(2)').textContent.trim().split('\n')[1];

                const dateMatch = (!start || !end) ? true : (rowDate >= start && rowDate <= end);
                const categoryMatch = selectedCategory === 'all' ? true : rowCategory === selectedCategory;
                const subcategoryMatch = selectedSubcategory === 'all' ? true : rowSubcategory === selectedSubcategory;

                const shouldShow = dateMatch && categoryMatch && subcategoryMatch;
                row.style.display = shouldShow ? '' : 'none';
                if (shouldShow) visibleCount++;
            });

            // Filter mobile view
            mobileCards.forEach(card => {
                const dateStr = card.querySelector('.text-gray-900:last-child').textContent.trim();
                const cardDate = new Date(dateStr.split(' ')[0].split('-').reverse().join('-')).getTime();
                const cardCategory = card.querySelector('.text-gray-900').textContent.trim().split('\n')[0];
                const cardSubcategory = card.querySelector('.text-gray-500').textContent.trim();

                const dateMatch = (!start || !end) ? true : (cardDate >= start && cardDate <= end);
                const categoryMatch = selectedCategory === 'all' ? true : cardCategory === selectedCategory;
                const subcategoryMatch = selectedSubcategory === 'all' ? true : cardSubcategory === selectedSubcategory;

                card.style.display = (dateMatch && categoryMatch && subcategoryMatch) ? '' : 'none';
            });

            updateFilterSummary(startDate, endDate, selectedCategory, selectedSubcategory, visibleCount);
        }

        // Update the summary function to include subcategory
        function updateFilterSummary(startDate, endDate, category, subcategory, visibleCount) {
            const existingSummary = document.getElementById('filterSummary');
            if (existingSummary) existingSummary.remove();

            const summary = document.createElement('div');
            summary.id = 'filterSummary';
            summary.className = 'mt-4 p-2 bg-blue-50 text-blue-700 rounded-md text-sm';

            let summaryText = `<i class="fa-solid fa-info-circle"></i> Menampilkan ${visibleCount} laporan`;

            if (category !== 'all') {
                summaryText += ` dengan kategori "${category}"`;
                if (subcategory !== 'all') {
                    summaryText += ` (${subcategory})`;
                }
            }

            if (startDate && endDate) {
                summaryText += ` dari ${startDate} sampai ${endDate}`;
            }

            summary.innerHTML = summaryText;

            const filterControls = document.querySelector('.mt-4.flex');
            filterControls.parentNode.insertBefore(summary, filterControls.nextSibling);
        }

        // Add event listeners
        document.getElementById('categoryFilter').addEventListener('change', function() {
            updateSubcategoryOptions();
            filterReports();
        });
        document.getElementById('subcategoryFilter').addEventListener('change', filterReports);

        // Initialize subcategories on page load
        updateSubcategoryOptions();


        function filterReports() {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            const selectedCategory = document.getElementById('categoryFilter').value;
            const selectedImpact = document.getElementById('impactFilter').value;

            const start = startDate ? new Date(startDate).getTime() : null;
            const end = endDate ? new Date(endDate).getTime() + (24 * 60 * 60 * 1000 - 1) : null;

            const rows = document.querySelectorAll('tbody tr');
            const mobileCards = document.querySelectorAll('.md\\:hidden > div');

            let visibleCount = 0;

            // Filter desktop view
            rows.forEach(row => {
                const dateStr = row.querySelector('td:nth-child(4)').textContent.trim();
                const rowDate = new Date(dateStr.split(' ')[0].split('-').reverse().join('-')).getTime();
                const rowCategory = row.querySelector('td:nth-child(2)').textContent.trim();
                const rowImpacts = JSON.parse(row.dataset.impacts || '[]');

                const dateMatch = (!start || !end) ? true : (rowDate >= start && rowDate <= end);
                const categoryMatch = selectedCategory === 'all' ? true : rowCategory === selectedCategory;
                const impactMatch = selectedImpact === 'all' ? true : rowImpacts.includes(selectedImpact);

                const shouldShow = dateMatch && categoryMatch && impactMatch;
                row.style.display = shouldShow ? '' : 'none';
                if (shouldShow) visibleCount++;
            });

            // Filter mobile view
            mobileCards.forEach(card => {
                const dateStr = card.querySelector('.text-gray-900:last-child').textContent.trim();
                const cardDate = new Date(dateStr.split(' ')[0].split('-').reverse().join('-')).getTime();
                const cardCategory = card.querySelector('.text-gray-900').textContent.trim();
                const cardImpacts = JSON.parse(card.dataset.impacts || '[]');

                const dateMatch = (!start || !end) ? true : (cardDate >= start && cardDate <= end);
                const categoryMatch = selectedCategory === 'all' ? true : cardCategory === selectedCategory;
                const impactMatch = selectedImpact === 'all' ? true : cardImpacts.includes(selectedImpact);

                card.style.display = (dateMatch && categoryMatch && impactMatch) ? '' : 'none';
            });

            updateFilterSummary(startDate, endDate, selectedCategory, selectedImpact, visibleCount);
        }

        function updateFilterSummary(startDate, endDate, category, impact, visibleCount) {
            const existingSummary = document.getElementById('filterSummary');
            if (existingSummary) existingSummary.remove();

            const summary = document.createElement('div');
            summary.id = 'filterSummary';
            summary.className = 'mt-4 p-2 bg-blue-50 text-blue-700 rounded-md text-sm';

            let summaryText = `<i class="fa-solid fa-info-circle"></i> Menampilkan ${visibleCount} laporan`;

            if (category !== 'all') {
                summaryText += ` dengan kategori "${category}"`;
            }

            if (impact !== 'all') {
                summaryText += ` dan dampak "${impact}"`;
            }

            if (startDate && endDate) {
                summaryText += ` dari ${startDate} sampai ${endDate}`;
            }

            summary.innerHTML = summaryText;

            const filterControls = document.querySelector('.mt-4.flex');
            filterControls.parentNode.insertBefore(summary, filterControls.nextSibling);
        }

        // Add event listener for impact filter
        document.getElementById('impactFilter').addEventListener('change', filterReports);

        let reportIdToDelete = null;

        function confirmDelete(reportId) {
            reportIdToDelete = reportId;
            document.getElementById('deleteModal').classList.remove('hidden');

            document.getElementById('deleteConfirmButton').onclick = function() {
                deleteReport(reportId);
            };
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            reportIdToDelete = null;
        }

        function deleteReport(reportId) {
            fetch(`/admin/reports/${reportId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove the row from the table
                        const row = document.querySelector(`tr[data-report-id="${reportId}"]`);
                        if (row) row.remove();

                        // Close modal
                        closeDeleteModal();

                        // Show success message
                        alert('Laporan berhasil dihapus');
                    } else {
                        throw new Error('Failed to delete report');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Gagal menghapus laporan');
                });
        }
    </script>
</body>

</html>