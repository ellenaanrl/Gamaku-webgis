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
    <div class="flex-grow">
        <!-- Header/Navigation -->
        <nav class="bg-[#083d62] shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center space-x-3">
                        <img src="{{ asset('images/logo kuningg.png') }}" alt="Gamaku Logo" class="h-8 w-auto object-contain"/>
                        <h1 class="text-2xl font-bold text-[#fdcb2c]">Laporan Kerusakan</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('admin.map') }}" class="text-gray-300 hover:text-white active:text-[#fdcb2c] px-3 py-2 text-sm font-medium">Peta Data Spasial</a>
                        <a href="{{ route('admin.reportmap') }}" class="text-gray-300 hover:text-white active:text-[#fdcb2c] px-3 py-2 text-sm font-medium">Peta Lokasi Kerusakan</a>
                        <a href="/admin/reports" class="text-[#fdcb2c] px-3 py-2 text-sm font-medium">Laporan Kerusakan</a>
                        <div class="flex items-center bg-[#0a4a75] rounded-lg px-3 py-2">
                            <i class="fas fa-user-shield text-white mr-2"></i>
                            <span class="text-white text-sm font-medium">{{ Auth::user()->name }}</span>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="bg-red-600 text-white hover:bg-red-700 px-4 py-2 rounded-md text-sm font-medium">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            @if(session('status_updated'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    {{ session('status_updated') }}
                </div>
            @endif

            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h2 class="text-lg font-medium text-gray-900"><i class="fa-solid fa-clipboard-list"></i>  Laporan Kerusakan</h2>
                    <p class="mt-1 text-sm text-gray-500">Kumpulan laporan kerusakan yang diajukan oleh pelapor</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-yellow-500 uppercase tracking-wider">
                                    <i class="fa-solid fa-user"></i>  Pelapor
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-yellow-500 uppercase tracking-wider">
                                    <i class="fa-solid fa-list"></i>  Kategori
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-yellow-500 uppercase tracking-wider">
                                    <i class="fa-solid fa-location-dot"></i>  Lokasi
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-yellow-500 uppercase tracking-wider">
                                    <i class="fa-solid fa-calendar-days"></i>  Tanggal
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-yellow-500 uppercase tracking-wider">
                                    <i class="fa-solid fa-list-check"></i>  Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-yellow-500 uppercase tracking-wider">
                                    <i class="fa-solid fa-circle-info"></i>  Info
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
                                <!-- <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        <a href="https://www.google.com/maps?q={{ $report->latitude }},{{ $report->longitude }}" 
                                           target="_blank" 
                                           class="text-indigo-600 hover:text-indigo-900">
                                            View on Map
                                        </a>
                                    </div>
                                </td> -->
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
                                    <a href="{{ Storage::url($report->photo_path) }}" 
                                       target="_blank"
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
            </div>
        </main>
    </div>

    <!-- Modal for viewing report details -->
    <div id="reportModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modalTitle">Detail Laporan Kerusakan</h3>
                <div class="mt-2" id="modalContent">
                    <ul class="divide-y divide-gray-200">
                        <li class="py-4">
                            <div class="mb-2">
                                <span class="font-semibold text-gray-700">Pelapor:</span>
                                <span class="text-gray-900" id="modalReporterName"></span>
                                <span class="ml-2 text-xs text-gray-500" id="modalDepartment"></span>
                                <span class="ml-2 text-xs text-gray-500" id="modalPhone"></span>
                                <span class="ml-2 text-xs text-gray-500" id="modalEmail"></span>
                            </div>
                            <div class="mb-2">
                                <span class="font-semibold text-gray-700">Kategori:</span>
                                <span class="text-gray-900" id="modalCategory"></span>
                                <span class="ml-2 text-xs text-gray-500" id="modalSubcategory"></span>
                            </div>
                            <div class="mb-2">
                                <span class="font-semibold text-gray-700">Lantai:</span>
                                <span class="text-gray-900" id="modalfloor"></span>
                                <span class="ml-2 text-xs text-gray-500" id="modalfloor"></span>
                            </div>
                            <div class="mb-2">
                                <span class="font-semibold text-gray-700">Deskripsi kerusakan:</span>
                                <span class="text-gray-900" id="modalDescription"></span>
                            </div>
                            <div class="mb-2">
                                <span class="font-semibold text-gray-700">Lokasi:</span>
                                <a href="#" id="modalLocation" target="_blank" class="text-indigo-600 hover:text-indigo-900">
                                    View on Map
                                </a>
                            </div>
                            <div class="mb-2">
                                <span class="font-semibold text-gray-700">Tanggal Laporan:</span>
                                <span class="text-gray-900" id="modalCreatedAt"></span>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-700">Status:</span>
                                <span class="text-xs rounded-full px-2 py-1" id="modalStatus"></span>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="mt-4">
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
            statusSpan.className = 'text-xs rounded-full px-2 py-1 ' +
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
