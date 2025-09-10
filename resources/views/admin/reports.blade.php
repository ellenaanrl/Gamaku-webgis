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
            </div>

            <!-- Desktop Table View (hidden on mobile) -->
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-yellow-500 uppercase tracking-wider">
                                <i class="fa-solid fa-user"></i> Pelapor
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-yellow-500 uppercase tracking-wider">
                                <i class="fa-solid fa-list"></i> Kategori
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-yellow-500 uppercase tracking-wider">
                                <i class="fa-solid fa-location-dot"></i> Lokasi
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-yellow-500 uppercase tracking-wider">
                                <i class="fa-solid fa-calendar-days"></i> Tanggal
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-yellow-500 uppercase tracking-wider">
                                <i class="fa-solid fa-list-check"></i> Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-yellow-500 uppercase tracking-wider">
                                <i class="fa-solid fa-circle-info"></i> Info
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($reports as $report)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $report->reporter_name }}</div>
                                <div class="text-sm font-medium text-gray-900">{{ $report->department }}</div>
                                <div class="text-sm font-medium text-gray-900">{{ $report->phone }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $report->category }}</div>
                                <div class="text-sm text-gray-500">{{ $report->subcategory }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $report->lokasi }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $report->created_at->format('d-m-Y H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
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

    <script>
        function showDetails(btn) {
            document.getElementById('modalReporterName').textContent = btn.getAttribute('data-reporter_name');
            document.getElementById('modalDepartment').textContent = btn.getAttribute('data-department');
            document.getElementById('modalPhone').textContent = btn.getAttribute('data-phone');
            document.getElementById('modalEmail').textContent = btn.getAttribute('data-email');
            document.getElementById('modalCategory').textContent = btn.getAttribute('data-category');
            document.getElementById('modalSubcategory').textContent = btn.getAttribute('data-subcategory');
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
    </script>
</body>

</html>