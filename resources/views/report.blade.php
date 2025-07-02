<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporkan Kerusakan - Gamaku WebGIS</title>
      <!-- Custom Fonts -->
    <link href="{{ asset('css/fonts.css') }}" rel="stylesheet">
    
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
    
    <!-- Leaflet CSS and JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <style>
        body {
            font-family: 'Gama Sans', sans-serif;
        }
        #map {
            height: 300px;
            width: 100%;
        }
    </style>
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
                        <a href="/" class="text-gray-300 hover:text-white active:text-[#fdcb2c] px-3 py-2 text-sm font-medium">Beranda</a>
                        <a href="/map" class="text-gray-300 hover:text-white active:text-[#fdcb2c] px-3 py-2 text-sm font-medium">Peta</a>
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
                        <a href="/management" class="text-gray-300 hover:text-white active:text-[#fdcb2c] px-3 py-2 text-sm font-medium">Laporkan Kerusakan</a>
                        @auth
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="bg-red-600 text-white hover:bg-red-700 px-4 py-2 rounded-md text-sm font-medium">
            Logout
        </button>
    </form>
@else
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
                <h2 class="text-3xl font-bold text-gray-900 mb-8">Laporkan Kerusakan</h2>
                
                <div class="bg-white rounded-lg shadow p-6">
                    @if($errors->any())
                    <div class="bg-red-50 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <strong class="font-bold">Terdapat kesalahan:</strong>
                        <ul class="mt-2 list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="bg-red-50 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <strong class="font-bold">Error:</strong>
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                    @endif

                    <form action="{{ route('report.store') }}" method="POST" class="space-y-6" enctype="multipart/form-data">
                        @csrf
                        
                        <div>                            <label for="reporter_name" class="block font-medium">Nama Pelapor</label>
                            <input type="text" name="reporter_name" id="reporter_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>

                        <div>
                            <label for="position" class="block font-medium">Jabatan/posisi</label>
                            <input type="text" name="position" id="position" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>

                        <div>
                            <label for="department" class="block font-medium">Fakultas/departemen</label>
                            <input type="text" name="department" id="department" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>

                        <div>
                            <label for="phone" class="block font-medium">No telepon (whatsapp)</label>
                            <input type="text" name="phone" id="phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>

                        <div>
                            <label for="email" class="block font-medium">Email</label>
                            <input type="email" name="email" id="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>

                        <div class="mb-4">
    <label for="category" class="block font-medium">Kategori Kerusakan</label>
    <select id="category" name="category" class="w-full border rounded px-3 py-2" required>
        <option value="">Pilih kategori</option>
        <option value="jalan">Jalan</option>
        <option value="bangunan">Bangunan</option>
    </select>
</div>

<div class="mb-4">
    <label for="subcategory" class="block font-medium">Subkategori Kerusakan</label>
    <select id="subcategory" name="subcategory" class="w-full border rounded px-3 py-2" required>
        <option value="">Pilih subkategori</option>
    </select>
</div>


                        <div>
                            <label for="kategori" class="block font-medium">Deskripsi Kerusakan</label>
                            <textarea name="description" id="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required></textarea>
                        </div>

                        <div>
                            <label for="kategori" class="block font-medium">Lokasi Kerusakan</label>
                            <div id="map" class="rounded-lg border border-gray-300 mb-2"></div>
                            <input type="hidden" name="latitude" id="latitude" required>
                            <input type="hidden" name="longitude" id="longitude" required>
                            <p class="text-sm text-gray-500">Klik pada peta untuk menandai lokasi kerusakan</p>
                        </div>

                        <div>
                            <label for="kategori" class="block font-medium">Foto Kerusakan</label>
                            <input type="file" name="photo" id="photo" accept="image/*" class="mt-1 block w-full" required>
                        </div>

                        
                        @if(session('report_submitted'))
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                                <strong class="font-bold">Laporan berhasil dikirim!</strong>
                                <span class="block sm:inline">Anda dapat melihat status laporan anda di bawah!</span>
                            </div>
                        @endif

                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                Kirim Laporan
                            </button>
                        </div>
                    </form>
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
        // Initialize the map
        var map = L.map('map').setView([-7.77086217621573, 110.37913568656498], 13);
        var marker = null;

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Add Geolocation Button
        var geoBtn = L.control({position: 'topleft'});
        geoBtn.onAdd = function(map) {
            var btn = L.DomUtil.create('button', 'leaflet-bar leaflet-control leaflet-control-custom');
            btn.innerHTML = '📍 Lokasi Saya';
            btn.style.backgroundColor = '#fff';
            btn.style.padding = '6px 10px';
            btn.style.cursor = 'pointer';
            btn.style.fontSize = '14px';
            btn.style.border = 'none';
            btn.style.borderRadius = '4px';
            btn.title = 'Gunakan lokasi saya';
            btn.onclick = function(e) {
            e.stopPropagation();
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                var lat = position.coords.latitude;
                var lng = position.coords.longitude;
                map.setView([lat, lng], 16);
                if (marker) {
                    map.removeLayer(marker);
                }
                marker = L.marker([lat, lng]).addTo(map);
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;
                });
            } else {
                alert('Geolocation tidak didukung browser Anda.');
            }
            };
            return btn;
        };
        geoBtn.addTo(map);

        // Handle map clicks
        map.on('click', function(e) {
            if (marker) {
                map.removeLayer(marker);
            }
            marker = L.marker(e.latlng).addTo(map);
            
            // Update hidden form fields
            document.getElementById('latitude').value = e.latlng.lat;
            document.getElementById('longitude').value = e.latlng.lng;
        });        const subcategoryOptions = {
        jalan: [
            { value: 'permukaan', text: 'Permukaan: retak, lubang, bergelombang atau tidak rata' },
            { value: 'marka_rambu', text: 'Marka dan Rambu: pudar, hilang, rusak' },
            { value: 'trotoar', text: 'Trotoar: rusak, tidak ramah disabilitas' },
        ],
        bangunan: [
            { value: 'struktural', text: 'Struktural: dinding, atap, lantai' },
            { value: 'non_struktural', text: 'Non-struktural: pintu, jendela, plafon' },
            { value: 'instalasi', text: 'Instalasi: listrik, lampu, saklar, kloset, AC, keran air' },
            { value: 'aksesibilitas', text: 'Aksesibilitas: lift, toilet difabel, jalur kursi roda' },
        ],
    };

    document.getElementById('category').addEventListener('change', function () {
        const selectedCategory = this.value;
        const subcategorySelect = document.getElementById('subcategory');        // Clear subcategory options
        subcategorySelect.innerHTML = '<option value="">Pilih subkategori</option>';

        // Populate subcategory based on selected category
        if (subcategoryOptions[selectedCategory]) {
            subcategoryOptions[selectedCategory].forEach(option => {
                const opt = document.createElement('option');
                opt.value = option.value;
                opt.textContent = option.text;
                subcategorySelect.appendChild(opt);
            });
        }
    });
    </script>
</body>
</html>