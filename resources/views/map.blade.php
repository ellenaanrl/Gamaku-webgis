<!DOCTYPE html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Peta - Gamaku WebGIS</title> <!-- Custom Fonts -->
    <link href="{{ asset('css/fonts.css') }}" rel="stylesheet" />
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

    <!-- Leaflet CSS and JS -->
    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        body {
            font-family: 'Gama Sans', sans-serif;
        }

        #map {
            height: 72vh;
            /* desktop tetap proporsional */
            min-height: 245px;
            width: 100%;
        }

        @media (max-width: 640px) {
            #map {
                width: 100%;
                min-height: 250px;
                height: calc(100vh - 280px);
                /* 100vh dikurangi tinggi header+filter+footer, sesuaikan angka */
            }

        }


        /* Let menu always above map */
        .mobile-menu {
            z-index: 9999 !important;
        }

        /* Pastikan map dan konten utama tidak menutupi menu mobile */
        main,
        #map {
            position: relative;
            z-index: 1;
        }

        /* Mobile menu selalu di atas */
        .mobile-menu {
            position: absolute;
            top: 64px;
            /* tinggi navbar */
            left: 0;
            right: 0;
            z-index: 9999 !important;
        }


        /* Tempatkan legenda tepat di bawah layer control ke-2 */
        .leaflet-top.leaflet-left .legend-control {
            margin-top: 127px !important; /* Adjust the margin-top to position below other controls */
            margin-left: 12px !important; /* Add some left margin */
            width: 30px !important;
            height: 30px !important;
            position: absolute !important;
            z-index: 1000 !important;
            top: 0 !important;
            left: 0 !important;
        }

        /* Compact legend control styling */
        .legend-control {
            background: white !important;
            border-radius: 4px !important;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.65) !important;
            padding: 0 !important;
            margin: 0 !important;
            width: auto !important;
            min-width: 32px !important;
            max-width: 32px !important;
        }

        .legend-toggle-btn {
            width: 34px !important;
            height: 34px !important;
            background: white !important;
            border: none !important;
            cursor: pointer !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 16px !important;
            color: #333 !important;
            padding: 0 !important;
            margin: 0 !important;
            border-radius: 4px !important;
        }

        .legend-toggle-btn:hover {
            background-color: #f4f4f4 !important;
        }

        .legend-content {
            position: absolute;
            left: 40px;
            top: 0;
            background: white;
            border-radius: 4px;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.65);
            padding: 8px;
            min-width: 200px;
            max-width: 250px;
            max-height: 245px;
            overflow-y: auto;
            font-size: 12px;
            z-index: 1000;
            display: none;
        }

        .legend-section {
            margin-bottom: 8px;
        }

        .legend-section h4 {
            margin: 0 0 4px 0;
            font-size: 13px;
            font-weight: bold;
            color: #083d62;
            border-bottom: 1px solid #eee;
            padding-bottom: 2px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            margin-bottom: 3px;
            font-size: 11px;
        }

        .legend-item i {
            width: 20px;
            height: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 6px;
            background: white;
            border-radius: 50%;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }

        .legend-color-box {
            width: 16px;
            height: 12px;
            border: 1px solid #888;
            margin-right: 6px;
            display: inline-block;
        }
    </style>
</head>

<body class="antialiased bg-gray-50 flex flex-col min-h-screen">
    <!-- Wrapper container agar footer di bawah -->
    <div class="relative min-h-screen flex-grow">
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
                        <a href="/map" class="text-[#fdcb2c] px-3 py-2 text-sm font-medium whitespace-nowrap">
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
                            class="absolute top-16 left-0 right-0 bg-[#083d62] border-t border-[#0a4a75] shadow-lg z-50 mobile-menu">
                            <div class="px-4 py-2 space-y-2">
                                <a href="/" class="block text-gray-300 hover:text-white px-3 py-2 text-sm font-medium">
                                    <i class="fa-solid fa-house mr-2"></i>Beranda
                                </a>
                                <a href="/map" class="block text-[#fdcb2c] hover:bg-[#0a4a75] px-3 py-2 text-sm font-medium">
                                    <i class="fa-solid fa-map mr-2"></i>Peta
                                </a>
                                <div class="border-l-2 border-[#fdcb2c]">
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
        <main class="max-w-7xl mx-auto py-2 sm:py-4 px-2 sm:px-6 lg:px-8">
            <div class="px-2 py-2 sm:px-0">
                <h2 class="text-xl sm:text-2xl font-bold mb-2 sm:mb-4">Peta Interaktif Jalan dan Bangunan </h2>
                <div class="mb-2 sm:mb-4 flex flex-col sm:flex-row gap-2 sm:gap-4">
                    <input type="text" id="globalSearchInput" placeholder="Cari nama, unit, jenis, atau nama jalan/polygon..." class="w-full sm:w-64 px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#083d62] focus:border-transparent text-xs sm:text-sm">
                    <select id="categoryFilter" class="px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#083d62] focus:border-transparent text-xs sm:text-sm">
                        <option value="all">Pilih data</option>
                        <option value="point">Titik</option>
                        <option value="polygon">Polygon</option>
                        <option value="jalan">Jalan</option>
                    </select>
                    <select id="unitFilter" placeholder="Pilih unit" class="px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#083d62] focus:border-transparent text-xs sm:text-sm">
                        <option value="">Pilih unit</option>
                    </select>
                    <select id="subCategoryFilter" placeholder="Pilih kategori"  class="px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#083d62] focus:border-transparent text-xs sm:text-sm">
                        <option value="">Pilih kategori</option>
                    </select>
                </div>
                <div id="map" class="border-4 rounded-lg" style="border-color: #083d62;"></div>
            </div>
        </main>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-gray-400 py-4 sm:py-6">
        <div class="max-w-7xl mx-auto px-2 sm:px-4 lg:px-8 text-center">
            © 2025 Gamaku WebGIS. All rights reserved.
        </div>
    </footer>

    <script>
        // Base layers
        var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        });

        var esri = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}', {
            maxZoom: 19,
            attribution: 'Tiles &copy; Esri'
        });

        var satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            maxZoom: 19,
            attribution: 'Tiles © Esri'
        }); // Initialize the map with default base layer
        var map = L.map('map', {
            center: [-7.770995416459988, 110.379045950522],
            zoom: 15,
            layers: [satellite] // default layer (Esri satellite)
        });

        fetch('/geojson/AOI_UGM.geojson')
            .then(res => res.json())
            .then(data => {
                L.geoJSON(data, {
                    style: {
                        color: '#ffff00',
                        weight: 2,
                        fillOpacity: 0
                    },
                    onEachFeature: function(feature, layer) {
                        layer.bindPopup("Batas wilayah Kampus UGM");
                    }
                }).addTo(map);
            })
            .catch(err => console.error("Gagal memuat AOI_UGM.geojson:", err));

        // Basemaps for control
        var baseMaps = {
            "Esri Satellite": satellite,
            "Esri Streets": esri,
            "OpenStreetMap": osm
        };

        // Create overlay layers object for the layer control
        var overlayMaps = {}; // Function to create popup content from properties
        function createPopupContent(properties) {
            let content = '<div class="p-2">';
            for (let key in properties) {
                content += `<div class="mb-1"><strong>${key}:</strong> ${properties[key]}</div>`;
            }
            content += '</div>';
            return content;
        }

        // Create layer groups to hold our layers
        var buildingPolygons = new L.LayerGroup();
        var buildingPoints = new L.LayerGroup();
        var jalanPolygons = new L.LayerGroup();

        // Add layers to the overlay control
        overlayMaps["Bangunan (Polygon)"] = buildingPolygons;
        overlayMaps["Bangunan (Titik)"] = buildingPoints;
        overlayMaps["Jalan"] = jalanPolygons;

        // Add the combined layer control to the map (base layers and overlays)
        L.control.layers(baseMaps, overlayMaps).addTo(map);


        // 3. Toggle logic
        function togglePanel(id) {
            const panel = document.getElementById(id);
            if (panel) {
                panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
            }
        }

        // Add geolocation button
        var locateBtn = L.control({
            position: 'topleft'
        });
        locateBtn.onAdd = function(map) {
            var div = L.DomUtil.create('div', 'leaflet-bar leaflet-control');
            div.innerHTML = '<button id="locateMeBtn" title="Lokasi Saya" style="background:white; border-radius:10%; padding:6px 10px; cursor:pointer;"><i class="fas fa-location-crosshairs"></i></button>';
            return div;
        };
        locateBtn.addTo(map);

        setTimeout(() => {
            var locateBtnEl = document.getElementById('locateMeBtn');
            if (locateBtnEl) {
                locateBtnEl.onclick = function() {
                    if (!navigator.geolocation) {
                        alert('Geolocation tidak didukung browser Anda.');
                        return;
                    }
                    locateBtnEl.disabled = true;
                    locateBtnEl.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                    navigator.geolocation.getCurrentPosition(function(position) {
                        var lat = position.coords.latitude;
                        var lng = position.coords.longitude;
                        var userMarker = L.marker([lat, lng], {
                            icon: L.divIcon({
                                html: '<i class="fas fa-location-dot fa-lg" style="color:#083d62;"></i>',
                                className: '',
                                iconSize: [24, 24],
                                iconAnchor: [12, 12]
                            })
                        }).addTo(map);
                        map.setView([lat, lng], 17);
                        userMarker.bindPopup('Lokasi Anda').openPopup();
                        locateBtnEl.innerHTML = '<i class="fas fa-location-crosshairs"></i>';
                        locateBtnEl.disabled = false;
                    }, function() {
                        alert('Tidak dapat mengambil lokasi Anda.');
                        locateBtnEl.innerHTML = '<i class="fas fa-location-crosshairs"></i>';
                        locateBtnEl.disabled = false;
                    });
                };
            }
        }, 0);

        // Custom collapsible legend control
        var compactLegend = L.control({
            position: 'topleft'
        });

        compactLegend.onAdd = function(map) {
            const container = L.DomUtil.create('div', 'legend-control');

            container.innerHTML = `
                <button class="legend-toggle-btn" id="legendToggleBtn" title="Legend">
                    <i class="fa-solid fa-list"></i>
                </button>
                <div class="legend-content" id="legendContent">
                    <div class="legend-section">
                        <h4><i class="fa-solid fa-location-dot"></i> Legenda Titik</h4>
                        <div class="legend-item">
                            <i class="fas fa-school" style="color:#fdcb2c;"></i>
                            <span>Gedung Kuliah/Departemen</span>
                        </div>
                        <div class="legend-item">
                            <i class="fas fa-mosque" style="color:#dc3545;"></i>
                            <span>Tempat Ibadah</span>
                        </div>
                        <div class="legend-item">
                            <i class="fas fa-briefcase" style="color:#198754;"></i>
                            <span>Kantor/Sekretariat</span>
                        </div>
                        <div class="legend-item">
                            <i class="fas fa-user-graduate" style="color:#17a2b8;"></i>
                            <span>Pusat Studi</span>
                        </div>
                        <div class="legend-item">
                            <i class="fas fa-bed" style="color:#fd7e14;"></i>
                            <span>Asrama/Hotel</span>
                        </div>
                        <div class="legend-item">
                            <i class="fas fa-chalkboard-user" style="color:#6c757d;"></i>
                            <span>Auditorium/Aula</span>
                        </div>
                        <div class="legend-item">
                            <i class="fas fa-utensils" style="color:#28a745;"></i>
                            <span>Kantin/Kafe</span>
                        </div>
                        <div class="legend-item">
                            <i class="fas fa-book" style="color:#343a40;"></i>
                            <span>Perpustakaan</span>
                        </div>
                        <div class="legend-item">
                            <i class="fas fa-flask" style="color:#6f42c1;"></i>
                            <span>Laboratorium</span>
                        </div>
                        <div class="legend-item">
                            <i class="fas fa-hospital" style="color:#e83e8c;"></i>
                            <span>Rumah Sakit</span>
                        </div>
                    </div>
                    
                    <div class="legend-section">
                        <h4><i class="fa-solid fa-square-full"></i> Legenda Polygon</h4>
                        <div class="legend-item">
                            <span class="legend-color-box" style="background-color:#d96512ff;"></span>
                            <span>Universitas</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color-box" style="background-color:#1bba82ff;"></span>
                            <span>Fakultas/Pascasarjana</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color-box" style="background-color:#f15db8d6;"></span>
                            <span>Fasilitas</span>
                        </div>
                    </div>
                </div>
            `;

            // Prevent map interactions when clicking on the control
            L.DomEvent.disableClickPropagation(container);
            L.DomEvent.disableScrollPropagation(container);

            return container;
        };

        compactLegend.addTo(map);


        setTimeout(() => {
            const toggleBtn = document.getElementById('legendToggleBtn');
            const legendContent = document.getElementById('legendContent');

            if (toggleBtn && legendContent) {
                toggleBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const isVisible = legendContent.style.display === 'block';
                    legendContent.style.display = isVisible ? 'none' : 'block';

                    // Change icon based on state
                    const icon = toggleBtn.querySelector('i');
                    if (isVisible) {
                        icon.className = 'fa-solid fa-list';
                    } else {
                        icon.className = 'fa-solid fa-times';
                    }
                });

                // Close legend when clicking outside
                map.on('click', function() {
                    legendContent.style.display = 'none';
                    toggleBtn.querySelector('i').className = 'fa-solid fa-list';
                });
            }
        }, 100);

        // --- Unified Filtering & Search Logic ---
        let allPointFeatures = [];
        let allPolygonFeatures = [];
        let allJalanFeatures = [];
        let colorMap = {};

        function updateUnitFilterOptions() {
            const category = document.getElementById('categoryFilter').value;
            const subcat = document.getElementById('subCategoryFilter').value;
            const unitFilter = document.getElementById('unitFilter');
            while (unitFilter.options.length > 1) unitFilter.remove(1);

            let set = new Set();

            if (category === 'point') {
                allPointFeatures.forEach(f => {
                    if (
                        f.properties &&
                        (subcat === '' || f.properties.jenis_bang === subcat) &&
                        f.properties.jenis_bang
                    ) {
                        set.add(f.properties.unit); // atau 'nama', tergantung field yang dimaksud sebagai unit
                    }
                });
            } else if (category === 'polygon') {
                allPolygonFeatures.forEach(f => {
                    if (
                        f.properties &&
                        (subcat === '' || f.properties.nama === subcat) &&
                        f.properties.kategori
                    ) {
                        set.add(f.properties.kategori);
                    }
                });
            } else if (category === 'jalan') {
                allJalanFeatures.forEach(f => {
                    if (
                        f.properties &&
                        (subcat === '' || f.properties.nama === subcat) &&
                        f.properties.unit
                    ) {
                        set.add(f.properties.unit);
                    }
                });
            }

            Array.from(set).sort().forEach(function(val) {
                const opt = document.createElement('option');
                opt.value = val;
                opt.textContent = val;
                unitFilter.appendChild(opt);
            });
        }

        function updateSubCategoryOptions() {
            const category = document.getElementById('categoryFilter').value;
            const unit = document.getElementById('unitFilter').value;
            const subCategoryFilter = document.getElementById('subCategoryFilter');
            while (subCategoryFilter.options.length > 1) subCategoryFilter.remove(1);
            let set = new Set();
            if (category === 'point') {
                const unit = document.getElementById('unitFilter').value;
                allPointFeatures.forEach(f => {
                    if (f.properties && f.properties.jenis_bang) set.add(f.properties.jenis_bang);
                });
            } else if (category === 'polygon') {
                const unit = document.getElementById('unitFilter').value;

                allPolygonFeatures.forEach(f => {
                    if (f.properties) {
                        const nama = f.properties.nama || f.properties.name;
                        const currentUnit = f.properties.kategori || '';

                        const matchUnit = (unit === 'Semua') || (unit === currentUnit);

                        if (matchUnit && nama && nama.trim() !== '') {
                            set.add(nama);
                        }
                    }
                });
            } else if (category === 'jalan') {
                allJalanFeatures.forEach(f => {
                    if (f.properties && f.properties.nama) set.add(f.properties.nama);
                });
            } else if (category === 'all') {

                const shouldFilterByUnit = unit && unit !== 'Semua' && unit !== '';

                allPointFeatures.forEach(f => {
                    if (f.properties && f.properties.nama) set.add(f.properties.nama);
                });
                allPolygonFeatures.forEach(f => {
                    if (f.properties) {
                        const nama = f.properties.nama || f.properties.name;
                        const currentUnit = f.properties.kategori || '';

                        if (nama) {
                            // If unit filter is active, only add if it matches
                            if (!shouldFilterByUnit || currentUnit === unit) {
                                set.add(nama);
                            }
                        }
                    }
                });
                allJalanFeatures.forEach(f => {
                    if (f.properties && f.properties.nama) set.add(f.properties.nama);
                });
            }
            Array.from(set).sort().forEach(function(val) {
                const opt = document.createElement('option');
                opt.value = val;
                opt.textContent = val;
                subCategoryFilter.appendChild(opt);
            });
        }

        // Fungsi untuk ambil HTML icon sesuai kategori titik
        function getPointIconHTML(jenis) {
            const iconMap = {
                'Gedung Kuliah/Departemen/Fakultas/Program Studi': ['fa-school', '#fdcb2c'],
                'Tempat Ibadah': ['fa-mosque', '#dc3545'],
                'Kantor/Sekretariat/Himpunan/Organisasi/Center': ['fa-briefcase', '#198754'],
                'Pusat Studi/Pusat Pengembangan': ['fa-user-graduate', '#17a2b8'],
                'Asrama Mahasiswa/Wisma/Hotel': ['fa-bed', '#fd7e14'],
                'Auditorium/Aula/Salesa': ['fa-chalkboard-user', '#6c757d'],
                'Kantin/Kafe/Tempat Makan/Co-Working Space': ['fa-utensils', '#28a745'],
                'Perpustakaan': ['fa-book', '#343a40'],
                'Laboratorium': ['fa-flask', '#6f42c1'],
                'Rumah Sakit': ['fa-hospital', '#e83e8c'],
                'Kantong Parkir': ['fa-square-parking', '#adb5bd'],
                'Mini Market/Swalayan': ['fa-store', '#ffc107'],
                'Taman/Lapangan': ['fa-tree', '#198754'],
                'Gedung Olahraga/Stadion': ['fa-dumbbell', '#0d6efd'],
                'SPAM': ['fa-water', '#0dcaf0'],
            };

            let matchedIcon = 'fa-map-marker-alt';
            let iconColor = '#fdcb2c';
            for (const key in iconMap) {
                if (jenis && jenis.includes(key)) {
                    matchedIcon = iconMap[key][0];
                    iconColor = iconMap[key][1];
                    break;
                }
            }
            return `<i class="fas ${matchedIcon}" style="color:${iconColor}; margin-right:6px;"></i>`;
        }


        function unifiedFilterAndDisplay() {
            const search = document.getElementById('globalSearchInput').value.trim().toLowerCase();
            const category = document.getElementById('categoryFilter').value;
            const unit = document.getElementById('unitFilter').value;
            const subcat = document.getElementById('subCategoryFilter').value.trim().toLowerCase();

            // Hide all layers first
            map.removeLayer(buildingPoints);
            map.removeLayer(buildingPolygons);
            map.removeLayer(jalanPolygons);
            if (category === 'point') {
                buildingPoints.clearLayers();
                const selectedUnit = document.getElementById('unitFilter').value;
                const selectedSubcat = document.getElementById('subCategoryFilter').value;
                const filtered = allPointFeatures.filter(function(f) {
                    const props = f.properties || {};
                    const nama = (props.nama || '').toLowerCase();
                    const unitVal = (props.unit || '');
                    const jenisVal = (props.jenis_bang || '');
                    const matchSearch = !search || nama.includes(search) || unitVal.toLowerCase().includes(search) || jenisVal.toLowerCase().includes(search);
                    const matchUnit = !selectedUnit || selectedUnit === '' || selectedUnit === 'Semua' || unitVal === selectedUnit;
                    const matchSub = !selectedSubcat || selectedSubcat === '' || selectedSubcat === 'Semua' || jenisVal === selectedSubcat || nama === selectedSubcat.toLowerCase();
                    return matchSearch && matchUnit && matchSub;
                });
                L.geoJSON({
                    type: 'FeatureCollection',
                    features: filtered
                }, {
                    pointToLayer: function(feature, latlng) {
                        const kategori = feature.properties && feature.properties.jenis_bang ? feature.properties.jenis_bang : 'Lainnya';
                        const iconMap = {
                            'Gedung Kuliah/Departemen/Fakultas/Program Studi': ['fa-school', '#fdcb2c'],
                            'Tempat Ibadah': ['fa-mosque', '#dc3545'],
                            'Kantor/Sekretariat/Himpunan/Organisasi/Center': ['fa-briefcase', '#198754'],
                            'Pusat Studi/Pusat Pengembangan': ['fa-user-graduate', '#17a2b8'],
                            'Asrama Mahasiswa/Wisma/Hotel': ['fa-bed', '#fd7e14'],
                            'Auditorium/Aula/Salesa': ['fa-chalkboard-user', '#6c757d'],
                            'Kantin/Kafe/Tempat Makan/Co-Working Space': ['fa-utensils', '#28a745'],
                            'Perpustakaan': ['fa-book', '#343a40'],
                            'Laboratorium': ['fa-flask', '#6f42c1'],
                            'Rumah Sakit': ['fa-hospital', '#e83e8c'],
                            'Kantong Parkir': ['fa-square-parking', '#adb5bd'],
                            'Mini Market/Swalayan': ['fa-store', '#ffc107'],
                            'Taman/Lapangan': ['fa-tree', '#198754'],
                            'Gedung Olahraga/Stadion': ['fa-dumbbell', '#0d6efd'],
                            'SPAM': ['fa-water', '#0dcaf0'],
                        };
                        let matchedIcon = 'fa-map-marker-alt';
                        let iconColor = '#fdcb2c';
                        for (const key in iconMap) {
                            if (kategori.includes(key)) {
                                matchedIcon = iconMap[key][0];
                                iconColor = iconMap[key][1];
                                break;
                            }
                        }
                        const icon = L.divIcon({
                            html: `<i class="fas ${matchedIcon} fa-lg" style="color: ${iconColor}; background-color: white; padding: 4px; border-radius: 30%; box-shadow: 0 0 4px rgba(0,0,0,0.2); display: inline-block; line-height: 1;"></i>`,
                            className: 'custom-div-icon',
                            iconSize: [30, 30],
                            iconAnchor: [15, 15],
                            popupAnchor: [0, -15]
                        });
                        return L.marker(latlng, {
                            icon: icon
                        });
                    },
                    onEachFeature: function(feature, layer) {
                        const nama = feature.properties?.nama || 'Tidak diketahui';
                        const jenis = feature.properties?.jenis_bang || '';
                        const unit = feature.properties?.unit || '';
                        layer.bindPopup(`<strong>${nama}</strong><br>${jenis}<br>${unit}`);
                    }
                }).addTo(buildingPoints);
                map.addLayer(buildingPoints);
            } else if (category === 'polygon') {
                buildingPolygons.clearLayers();
                const filtered = allPolygonFeatures.filter(function(f) {
                    const props = f.properties || {};
                    const nama = (props.nama || props.name || '').toLowerCase();
                    const kategori = props.kategori || '';
                    const matchSearch = !search || nama.includes(search);
                    const matchUnit = !unit || unit === 'Semua' || kategori === unit;
                    const matchSub = !subcat || nama === subcat;
                    return matchSearch && matchUnit && matchSub;
                });
                L.geoJSON({
                    type: 'FeatureCollection',
                    features: filtered
                }, {
                    style: function(feature) {
                        const kategori = feature.properties && feature.properties.kategori ? feature.properties.kategori : 'Lainnya';
                        return {
                            color: getKategoriColor(kategori),
                            weight: 2,
                            fillOpacity: 0.6
                        };
                    },
                    onEachFeature: function(feature, layer) {
                        const nama = feature.properties?.nama || 'Tidak diketahui';
                        const jmlLantai = feature.properties?.jml_lantai ? `<br>Jumlah Lantai: ${feature.properties.jml_lantai}` : '';
                        const foto = feature.properties?.foto;
                        const unit = feature.properties?.kategori || '';

                        let popupContent = `<div style="min-width: 200px;">
        <strong>${nama}</strong>${jmlLantai}<br>`;

                        if (foto) {
                            popupContent += `<img src="${foto}" alt="Foto ${nama}" 
            style="max-width: 200px; max-height: 150px; width: 100%; 
            object-fit: cover; border-radius: 8px; margin: 10px 0; 
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);" 
            onerror="this.style.display='none';">`;
                        }

                        // Collect point names inside this polygon
                        let pointsInside = [];
                        allPointFeatures.forEach(ptFeature => {
                            if (ptFeature.geometry && ptFeature.geometry.type === "Point") {
                                const lat = ptFeature.geometry.coordinates[1];
                                const lng = ptFeature.geometry.coordinates[0];
                                const latlng = L.latLng(lat, lng);
                                if (layer.getBounds().contains(latlng)) {
                                    const ptName = ptFeature.properties?.nama || 'Tanpa nama';
                                    const ptJenis = ptFeature.properties?.jenis_bang || '';
                                    pointsInside.push(getPointIconHTML(ptJenis) + ptName);

                                }
                            }
                        });

                        if (pointsInside.length > 0) {
                            popupContent += `<br><strong>Titik yang berada di bangunan ini:</strong><ul style="margin-top:4px;">`;
                            pointsInside.forEach(p => {
                                popupContent += `<li>${p}</li>`;
                            });
                            popupContent += `</ul>`;
                        }

                        popupContent += `<br>
        <button onclick="laporkanKerusakan(${layer.getBounds().getCenter().lat}, ${layer.getBounds().getCenter().lng})"
            class="mt-2 bg-red-600 text-white px-2 py-1 rounded hover:bg-red-700">
            Laporkan Kerusakan
        </button>
    </div>`;

                        layer.bindPopup(popupContent);
                    }

                }).addTo(buildingPolygons);
                map.addLayer(buildingPolygons);
            } else if (category === 'jalan') {
                jalanPolygons.clearLayers();
                const filtered = allJalanFeatures.filter(function(f) {
                    const props = f.properties || {};
                    const nama = (props.nama || '').toLowerCase();
                    const matchSearch = !search || nama.includes(search);
                    const matchSub = !subcat || nama === subcat;
                    return matchSearch && matchSub;
                });
                L.geoJSON({
                    type: 'FeatureCollection',
                    features: filtered
                }, {
                    style: {
                        color: "#a30303",
                        weight: 2
                    },
                    onEachFeature: function(feature, layer) {
                        layer.bindPopup(`<strong>${feature.properties.nama}</strong>`);
                    }
                }).addTo(jalanPolygons);
                map.addLayer(jalanPolygons);
            } else if (category === 'all') {
                // Show all layers, filter each
                buildingPoints.clearLayers();
                buildingPolygons.clearLayers();
                jalanPolygons.clearLayers();

                const selectedUnit = document.getElementById('unitFilter').value;
                const selectedSubcat = document.getElementById('subCategoryFilter').value;

                // Points
                const filteredPoints = allPointFeatures.filter(function(f) {
                    const props = f.properties || {};
                    const nama = (props.nama || '').toLowerCase();
                    const unit = (props.unit || '');
                    const jenis = (props.jenis_bang || '');
                    const matchSearch = !search || nama.includes(search) || unit.toLowerCase().includes(search) || jenis.toLowerCase().includes(search);
                    const matchUnit = !selectedUnit || selectedUnit === '' || selectedUnit === 'Semua' || unit === selectedUnit;
                    const matchSub = !selectedSubcat || selectedSubcat === '' || selectedSubcat === 'Semua' || jenis === selectedSubcat || nama === selectedSubcat.toLowerCase();
                    return matchSearch && matchUnit && matchSub;
                });
                L.geoJSON({
                    type: 'FeatureCollection',
                    features: filteredPoints
                }, {
                    pointToLayer: function(feature, latlng) {
                        const kategori = feature.properties && feature.properties.jenis_bang ? feature.properties.jenis_bang : 'Lainnya';
                        const iconMap = {
                            'Gedung Kuliah/Departemen/Fakultas/Program Studi': ['fa-school', '#fdcb2c'],
                            'Tempat Ibadah': ['fa-mosque', '#dc3545'],
                            'Kantor/Sekretariat/Himpunan/Organisasi/Center': ['fa-briefcase', '#198754'],
                            'Pusat Studi/Pusat Pengembangan': ['fa-user-graduate', '#17a2b8'],
                            'Asrama Mahasiswa/Wisma/Hotel': ['fa-bed', '#fd7e14'],
                            'Auditorium/Aula/Salesa': ['fa-chalkboard-user', '#6c757d'],
                            'Kantin/Kafe/Tempat Makan/Co-Working Space': ['fa-utensils', '#28a745'],
                            'Perpustakaan': ['fa-book', '#343a40'],
                            'Laboratorium': ['fa-flask', '#6f42c1'],
                            'Rumah Sakit': ['fa-hospital', '#e83e8c'],
                            'Kantong Parkir': ['fa-square-parking', '#adb5bd'],
                            'Mini Market/Swalayan': ['fa-store', '#ffc107'],
                            'Taman/Lapangan': ['fa-tree', '#198754'],
                            'Gedung Olahraga/Stadion': ['fa-dumbbell', '#0d6efd'],
                            'SPAM': ['fa-water', '#0dcaf0'],
                        };
                        let matchedIcon = 'fa-map-marker-alt';
                        let iconColor = '#fdcb2c';
                        for (const key in iconMap) {
                            if (kategori.includes(key)) {
                                matchedIcon = iconMap[key][0];
                                iconColor = iconMap[key][1];
                                break;
                            }
                        }
                        const icon = L.divIcon({
                            html: `<i class="fas ${matchedIcon} fa-lg" style="color: ${iconColor}; background-color: white; padding: 4px; border-radius: 30%; box-shadow: 0 0 4px rgba(0,0,0,0.2); display: inline-block; line-height: 1;"></i>`,
                            className: 'custom-div-icon',
                            iconSize: [30, 30],
                            iconAnchor: [15, 15],
                            popupAnchor: [0, -15]
                        });
                        return L.marker(latlng, {
                            icon: icon
                        });
                    },
                    onEachFeature: function(feature, layer) {
                        const nama = feature.properties?.nama || 'Tidak diketahui';
                        const jenis = feature.properties?.jenis_bang || '';
                        const unit = feature.properties?.unit || '';
                        // const foto = feature.properties?.dokumentasi;

                        let popupContent = `<strong>${nama}</strong><br>${jenis}<br>${unit}<br>`;

                        // if (foto) {
                        //     popupContent += `<img src="${foto}" alt="Foto Dokumentasi" style="max-width: 200px; border-radius: 8px; margin-top: 10px;">`;
                        // }

                        layer.bindPopup(popupContent);
                    }

                }).addTo(buildingPoints);
                map.addLayer(buildingPoints);

                // Polygons
                const filteredPolygons = allPolygonFeatures.filter(function(f) {
                    const props = f.properties || {};
                    const nama = (props.nama || props.name) ? (props.nama || props.name) : 'Lainnya';
                    const kategori = (props.kategori || '');
                    const matchSearch = !search || nama.toLowerCase().includes(search);
                    const matchUnit = !selectedUnit || selectedUnit === '' || selectedUnit === 'Semua' || kategori === selectedUnit;
                    const matchSub = !selectedSubcat || selectedSubcat === '' || selectedSubcat === 'Semua' || nama === selectedSubcat || nama.toLowerCase() === selectedSubcat.toLowerCase();
                    return matchSearch && matchUnit && matchSub;
                });
                const colorMap = {
                    'Universitas': '#d96512ff', // Orange-red
                    'Fakultas/Pascasarjana': '#1bba82ff', // Dark blue
                    'Fasilitas': '#f15db8d6', // Green
                };

                L.geoJSON({
                    type: 'FeatureCollection',
                    features: filteredPolygons
                }, {
                    style: function(feature) {
                        const nama = feature.properties && (feature.properties.nama || feature.properties.name) ?
                            (feature.properties.nama || feature.properties.name) :
                            'Lainnya';

                        return {
                            color: colorMap[nama] || '#007BFF', // default biru kalau tidak ada di colorMap
                            weight: 2,
                            fillOpacity: 0.6
                        };
                    },
                    onEachFeature: function(feature, layer) {
                        const nama = feature.properties?.nama || 'Tidak diketahui';
                        const jmlLantai = feature.properties?.jml_lantai ? `<br>Jumlah Lantai: ${feature.properties.jml_lantai}` : '';
                        const foto = feature.properties?.foto;
                        const unit = feature.properties?.kategori || '';

                        let popupContent = `<div style="min-width: 200px;">
        <strong>${nama}</strong>${jmlLantai}<br>`;

                        if (foto) {
                            popupContent += `<img src="${foto}" alt="Foto ${nama}" 
            style="max-width: 200px; max-height: 150px; width: 100%; 
            object-fit: cover; border-radius: 8px; margin: 10px 0; 
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);" 
            onerror="this.style.display='none';">`;
                        }

                        // Collect point names inside this polygon
                        let pointsInside = [];
                        allPointFeatures.forEach(ptFeature => {
                            if (ptFeature.geometry && ptFeature.geometry.type === "Point") {
                                const lat = ptFeature.geometry.coordinates[1];
                                const lng = ptFeature.geometry.coordinates[0];
                                const latlng = L.latLng(lat, lng);
                                if (layer.getBounds().contains(latlng)) {
                                    const ptName = ptFeature.properties?.nama || 'Tanpa nama';
                                    const ptJenis = ptFeature.properties?.jenis_bang || '';
                                    pointsInside.push(getPointIconHTML(ptJenis) + ptName);

                                }
                            }
                        });

                        if (pointsInside.length > 0) {
                            popupContent += `<br><strong>Titik yang berada di bangunan ini:</strong><ul style="margin-top:4px;">`;
                            pointsInside.forEach(p => {
                                popupContent += `<li>${p}</li>`;
                            });
                            popupContent += `</ul>`;
                        }

                        popupContent += `<br>
        <button onclick="laporkanKerusakan(${layer.getBounds().getCenter().lat}, ${layer.getBounds().getCenter().lng})"
            class="mt-2 bg-red-600 text-white px-2 py-1 rounded hover:bg-red-700">
            Laporkan Kerusakan
        </button>
    </div>`;

                        layer.bindPopup(popupContent);


                    }
                }).addTo(buildingPolygons);
                map.addLayer(buildingPolygons);

                // Jalan
                const filteredJalan = allJalanFeatures.filter(function(f) {
                    const props = f.properties || {};
                    const nama = (props.nama || '').toLowerCase();
                    const matchSearch = !search || nama.includes(search);
                    const matchSub = !selectedSubcat || selectedSubcat === '' || selectedSubcat === 'Semua' || nama === selectedSubcat.toLowerCase();
                    return matchSearch && matchSub;
                });
                L.geoJSON({
                    type: 'FeatureCollection',
                    features: filteredJalan
                }, {
                    style: {
                        color: "#a30303",
                        weight: 2
                    },
                    onEachFeature: function(feature, layer) {
                        layer.bindPopup(`<strong>${feature.properties.nama}</strong>`);
                    }
                }).addTo(jalanPolygons);
                map.addLayer(jalanPolygons);
            }
        }
        // Fetch and populate all data
        fetch('/point')
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                allPointFeatures = data.features || [];
                if (document.getElementById('categoryFilter').value === 'point') updateSubCategoryOptions();
            });
        // Update the polygon feature data handling section:
        fetch('/polygon')
            .then(response => response.json())
            .then(data => {
                // Mapping manual warna berdasarkan unit/kategori bangunan
                colorMap = {
                    'Universitas': '#d96512ff',
                    'Fakultas/Pascasarjana': '#1bba82ff',
                    'Fasilitas': '#f15db8d6',
                };

                allPolygonFeatures = data.features || [];

                // Add colors for units that aren't in the colorMap
                const defaultColors = [
                    '#6f42c1', '#fd7e14', '#17a2b8', '#4c8a65',
                    '#e83e8c', '#0d6efd', '#ffc107', '#198754',
                    '#a78fbf', '#0dcaf0', '#ff6f61', '#8bc34a',
                    '#ff9800', '#635f30', '#9c27b0'
                ];
                let colorIdx = 0;

                // Create set of unique units/kategori
                const uniqueUnits = new Set();
                allPolygonFeatures.forEach(f => {
                    const unit = f.properties?.kategori || 'Lainnya';
                    uniqueUnits.add(unit);
                    if (!colorMap[unit]) {
                        colorMap[unit] = defaultColors[colorIdx % defaultColors.length];
                        colorIdx++;
                    }
                });

                if (document.getElementById('categoryFilter').value === 'polygon') {
                    updateSubCategoryOptions();
                }

                // Generate dynamic legend based on units
                const polygonLegend = document.getElementById('polygonLegend');
                if (polygonLegend) {
                    let html = '<div class="mt-1">';
                    uniqueUnits.forEach(unit => {
                        html += `
                <div class='flex items-center mb-1'>
                    <span style='display:inline-block;width:18px;height:12px;background:${colorMap[unit]};border:1px solid #888;margin-right:6px;'></span>
                    ${unit}
                </div>`;
                    });
                    html += '</div>';
                    polygonLegend.innerHTML = '<strong>Bangunan (Polygon):</strong><br>' + html;
                }
            });
        fetch('/jalan')
            .then(res => res.json())
            .then(data => {
                allJalanFeatures = data.features || [];
                if (document.getElementById('categoryFilter').value === 'jalan') updateSubCategoryOptions();
            });
        // Event listeners
        document.getElementById('globalSearchInput').addEventListener('input', unifiedFilterAndDisplay);

        document.getElementById('categoryFilter').addEventListener('change', function() {
            // When category changes, update unit options first, then subcategory options
            updateUnitFilterOptions();
            updateSubCategoryOptions();

            // Reset both unit and subcategory filters to default
            document.getElementById('unitFilter').value = 'Semua';
            document.getElementById('subCategoryFilter').value = 'Semua';

            // Apply the filters to update the map display
            unifiedFilterAndDisplay();
        });

        document.getElementById('unitFilter').addEventListener('change', function() {
            // When unit changes, update subcategory options based on selected unit
            updateSubCategoryOptions();

            // Reset the subcategory filter value since the options have changed
            document.getElementById('subCategoryFilter').value = 'Semua';

            // Apply filters to update the display
            unifiedFilterAndDisplay();
        });

        document.getElementById('subCategoryFilter').addEventListener('change', function() {
            // When subcategory changes, just apply the filters
            // No need to update other filter options
            unifiedFilterAndDisplay();
        });

        function laporkanKerusakan(lat, lng) {
            const url = `/report?lat=${lat}&lng=${lng}`;
            window.location.href = url;
        }

        function getKategoriColor(kategori) {
            const kategoriColors = {
                'Universitas': '#d96512ff', // Orange-red
                'Fakultas/Pascasarjana': '#1bba82ff', // Dark blue
                'Fasilitas': '#f15db8d6', // Green
            };

            return kategoriColors[kategori] || '#6C757D'; // Default gray for unknown categories
        }
    </script>
</body>

</html>