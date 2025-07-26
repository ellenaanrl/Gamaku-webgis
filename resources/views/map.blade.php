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
            height: 60vh;
            min-height: 300px;
            width: 100%;
        }

        @media (max-width: 640px) {
            #map {
                height: 40vh;
                min-height: 200px;
            }
        }

        /* Tempatkan legenda tepat di bawah layer control ke-2 */
        .leaflet-top.leaflet-left .legend-control {
            margin-top: 120px;
            /* Sesuaikan angka ini agar pas di bawah layer control */
            margin-left: 10px;
            width: 180px;
        }
    </style>
</head>

<body class="antialiased bg-gray-50 flex flex-col min-h-screen">
    <!-- Wrapper container agar footer di bawah -->
    <div class="flex-grow">
        <!-- Header/Navigation -->
        <nav class="bg-[#083d62] shadow-sm">
            <div class="max-w-7xl mx-auto px-2 sm:px-4 lg:px-8">
                <div class="flex flex-col sm:flex-row justify-between h-auto sm:h-16 py-2 sm:py-0">
                    <div class="flex items-center space-x-2 mb-2 sm:mb-0">
                        @if(file_exists(public_path('images/logo kuningg.png')))
                        <img src="{{ asset('images/logo kuningg.png') }}" alt="Gamaku Logo" class="h-10 sm:h-12 w-auto object-contain" />
                        @endif
                        <h1 class="text-xl sm:text-2xl font-bold text-[#fdcb2c]">Gamaku WebGIS</h1>
                    </div>
                    <div class="flex flex-wrap items-center space-x-2 sm:space-x-4">
                        <a href="/" class="text-gray-300 hover:text-white active:text-[#fdcb2c] px-2 sm:px-3 py-1 sm:py-2 text-xs sm:text-sm font-medium"> <i class="fa-solid fa-house"></i> Beranda</a>
                        <a href="/map" class="text-[#fdcb2c] px-2 sm:px-3 py-1 sm:py-2 text-xs sm:text-sm font-medium"> <i class="fa-solid fa-map"></i> Peta</a>
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" @click.away="open = false"
                                class="text-gray-300 hover:text-white active:text-[#fdcb2c] px-2 sm:px-3 py-1 sm:py-2 text-xs sm:text-sm font-medium flex items-center focus:outline-none">
                                <i class="fa-solid fa-table mr-1"></i>Tabel
                                <svg class="ml-1 w-3 sm:w-4 h-3 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="open" x-transition
                                class="absolute right-0 mt-2 w-40 sm:w-48 bg-white border border-gray-200 rounded-md shadow-lg z-50">
                                <a href="/info" class="block px-4 py-2 text-xs sm:text-sm text-gray-700 hover:bg-gray-100"><i class="fa-solid fa-location-dot"></i> Tabel Titik Bangunan</a>
                                <a href="/infojalan" class="block px-4 py-2 text-xs sm:text-sm text-gray-700 hover:bg-gray-100"><i class="fa-solid fa-road"></i> Tabel Poligon Jalan</a>
                                <a href="/infobangunan" class="block px-4 py-2 text-xs sm:text-sm text-gray-700 hover:bg-gray-100"><i class="fa-solid fa-draw-polygon"></i> Tabel Poligon Bangunan</a>
                            </div>
                        </div>
                        <a href="/management" class="text-gray-300 hover:text-white active:text-[#fdcb2c] px-2 sm:px-3 py-1 sm:py-2 text-xs sm:text-sm font-medium"> <i class="fa-solid fa-flag"></i> Laporkan Kerusakan</a>
                        @auth
                        {{-- Jika user login --}}
                        <div class="flex items-center space-x-2 sm:space-x-6">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="bg-red-600 text-white hover:bg-red-700 px-2 sm:px-4 py-1 sm:py-2 rounded-md text-xs sm:text-sm font-medium">
                                    Logout
                                </button>
                            </form>

                            <div class="flex items-center text-xs sm:text-sm font-medium text-white space-x-2">
                                <i class="fa-solid fa-user"></i>
                                <span>{{ Auth::user()->name }}</span>
                            </div>
                        </div>
                        @else
                        {{-- Jika user belum login --}}
                        <a href="{{ route('login') }}" class="bg-white text-[#083d62] hover:bg-gray-100 active:bg-[#fdcb2c] px-2 sm:px-4 py-1 sm:py-2 rounded-md text-xs sm:text-sm font-medium">
                            Masuk/Daftar
                        </a>
                        @endauth

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
                        <option value="all">Semua</option>
                        <option value="point">Titik</option>
                        <option value="polygon">Polygon</option>
                        <option value="jalan">Jalan</option>
                    </select>
                    <select id="subCategoryFilter" class="px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#083d62] focus:border-transparent text-xs sm:text-sm">
                        <option value="">Semua</option>
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
                        color: '#ff0000',
                        weight: 2,
                        fillOpacity: 0
                    },
                    onEachFeature: function(feature, layer) {
                        layer.bindPopup("Area of Interest: AOI UGM");
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
        var collapsibleLegend = L.control({
            position: 'topleft'
        });

        collapsibleLegend.onAdd = function(map) {
            const container = L.DomUtil.create('div', 'leaflet-bar leaflet-control p-2 shadow bg-white rounded text-sm');

            container.innerHTML = `
        <button id="legendToggleBtnPoints" class="w-full text-left font-semibold mb-1 text-[#083d62]">
            <i class="fa-solid fa-location-dot"></i> Legenda Titik
        </button>
        <div id="legendContentPoints" style="max-height: 180px; overflow-y: auto;">
    <div class="flex items-center space-x-2 mb-1"><i class="fas fa-school" style="color:#fdcb2c; background:white; padding:4px; border-radius:50%;"></i><span>Gedung Kuliah/Departemen/Fakultas/Program Studi</span></div>
    <div class="flex items-center space-x-2 mb-1"><i class="fas fa-mosque" style="color:#dc3545; background:white; padding:4px; border-radius:50%;"></i><span>Tempat Ibadah</span></div>
    <div class="flex items-center space-x-2 mb-1"><i class="fas fa-briefcase" style="color:#198754; background:white; padding:4px; border-radius:50%;"></i><span>Kantor/Sekretariat/Himpunan/Organisasi/Center</span></div>
    <div class="flex items-center space-x-2 mb-1"><i class="fas fa-flask" style="color:#17a2b8; background:white; padding:4px; border-radius:50%;"></i><span>Pusat Studi/Pusat Pengembangan</span></div>
    <div class="flex items-center space-x-2 mb-1"><i class="fas fa-bed" style="color:#fd7e14; background:white; padding:4px; border-radius:50%;"></i><span>Asrama Mahasiswa/Wisma/Hotel</span></div>
    <div class="flex items-center space-x-2 mb-1"><i class="fas fa-chalkboard" style="color:#6c757d; background:white; padding:4px; border-radius:50%;"></i><span>Auditorium/Aula/Salesa</span></div>
    <div class="flex items-center space-x-2 mb-1"><i class="fas fa-utensils" style="color:#28a745; background:white; padding:4px; border-radius:50%;"></i><span>Kantin/Kafe/Tempat Makan/Co-Working Space</span></div>
    <div class="flex items-center space-x-2 mb-1"><i class="fas fa-book" style="color:#343a40; background:white; padding:4px; border-radius:50%;"></i><span>Perpustakaan</span></div>
    <div class="flex items-center space-x-2 mb-1"><i class="fas fa-vials" style="color:#6f42c1; background:white; padding:4px; border-radius:50%;"></i><span>Laboratorium</span></div>
    <div class="flex items-center space-x-2 mb-1"><i class="fas fa-hospital" style="color:#e83e8c; background:white; padding:4px; border-radius:50%;"></i><span>Rumah Sakit</span></div>
    <div class="flex items-center space-x-2 mb-1"><i class="fas fa-square-parking" style="color:#adb5bd; background:white; padding:4px; border-radius:50%;"></i><span>Kantong Parkir</span></div>
    <div class="flex items-center space-x-2 mb-1"><i class="fas fa-store" style="color:#ffc107; background:white; padding:4px; border-radius:50%;"></i><span>Mini Market/Swalayan</span></div>
    <div class="flex items-center space-x-2 mb-1"><i class="fas fa-tree" style="color:#198754; background:white; padding:4px; border-radius:50%;"></i><span>Taman/Lapangan</span></div>
    <div class="flex items-center space-x-2 mb-1"><i class="fas fa-dumbbell" style="color:#0d6efd; background:white; padding:4px; border-radius:50%;"></i><span>Gedung Olahraga/Stadion</span></div>
    <div class="flex items-center space-x-2 mb-1"><i class="fas fa-water" style="color:#0dcaf0; background:white; padding:4px; border-radius:50%;"></i><span>SPAM</span></div>
</div>
<hr class='my-2'>
<button id="legendToggleBtnPolygons" class="w-full text-left font-semibold mb-1 text-[#083d62]">
    <i class="fa-solid fa-square-full"></i> Legenda Polygon
</button>
<div id="legendContentPolygons" style="max-height: 180px; overflow-y: auto;">
    <div id="polygonLegend"><strong>Bangunan (Polygon):</strong><br><span style='font-size:12px;color:#666'>(Warna sesuai nama)</span></div>
</div>

    `;

            setTimeout(() => {
                // Point legend toggle
                const toggleBtnPoints = document.getElementById('legendToggleBtnPoints');
                const legendContentPoints = document.getElementById('legendContentPoints');
                legendContentPoints.style.display = 'none';
                toggleBtnPoints.addEventListener('click', () => {
                    const isHidden = legendContentPoints.style.display === 'none';
                    legendContentPoints.style.display = isHidden ? 'block' : 'none';
                });
                // Polygon legend toggle
                const toggleBtnPolygons = document.getElementById('legendToggleBtnPolygons');
                const legendContentPolygons = document.getElementById('legendContentPolygons');
                legendContentPolygons.style.display = 'none';
                toggleBtnPolygons.addEventListener('click', () => {
                    const isHidden = legendContentPolygons.style.display === 'none';
                    legendContentPolygons.style.display = isHidden ? 'block' : 'none';
                });
            }, 0);

            return container;
        };

        collapsibleLegend.addTo(map);

        // --- Unified Filtering & Search Logic ---
        let allPointFeatures = [];
        let allPolygonFeatures = [];
        let allJalanFeatures = [];
        let colorMap = {};

        function updateSubCategoryOptions() {
            const category = document.getElementById('categoryFilter').value;
            const subCategoryFilter = document.getElementById('subCategoryFilter');
            while (subCategoryFilter.options.length > 1) subCategoryFilter.remove(1);
            let set = new Set();
            if (category === 'point') {
                allPointFeatures.forEach(f => {
                    if (f.properties && f.properties.unit) set.add(f.properties.unit);
                });
            } else if (category === 'polygon') {
                allPolygonFeatures.forEach(f => {
                    if (f.properties && (f.properties.nama || f.properties.name)) set.add(f.properties.nama || f.properties.name);
                });
            } else if (category === 'jalan') {
                allJalanFeatures.forEach(f => {
                    if (f.properties && f.properties.nama) set.add(f.properties.nama);
                });
            } else if (category === 'all') {
                allPointFeatures.forEach(f => {
                    if (f.properties && f.properties.nama) set.add(f.properties.nama);
                });
                allPolygonFeatures.forEach(f => {
                    if (f.properties && (f.properties.nama || f.properties.name)) set.add(f.properties.nama || f.properties.name);
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

        function unifiedFilterAndDisplay() {
            const search = document.getElementById('globalSearchInput').value.trim().toLowerCase();
            const category = document.getElementById('categoryFilter').value;
            const subcat = document.getElementById('subCategoryFilter').value.trim().toLowerCase();

            // Hide all layers first
            map.removeLayer(buildingPoints);
            map.removeLayer(buildingPolygons);
            map.removeLayer(jalanPolygons);
            if (category === 'point') {
                buildingPoints.clearLayers();
                const filtered = allPointFeatures.filter(function(f) {
                    const props = f.properties || {};
                    const nama = (props.nama || '').toLowerCase();
                    const unit = (props.unit || '').toLowerCase();
                    const jenis = (props.jenis_bang || '').toLowerCase();
                    const matchSearch = !search || nama.includes(search) || unit.includes(search) || jenis.includes(search);
                    const matchSub = !subcat || unit === subcat;
                    return matchSearch && matchSub;
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
                    const matchSearch = !search || nama.includes(search);
                    const matchSub = !subcat || nama === subcat;
                    return matchSearch && matchSub;
                });
                L.geoJSON({
                    type: 'FeatureCollection',
                    features: filtered
                }, {
                    style: function(feature) {
                        const nama = feature.properties && (feature.properties.nama || feature.properties.name) ? (feature.properties.nama || feature.properties.name) : 'Lainnya';
                        return {
                            color: colorMap[nama] || '#007BFF',
                            weight: 2,
                            fillOpacity: 0.6
                        };
                    },
                    onEachFeature: function(feature, layer) {
                        layer.bindPopup(`
        <strong>${feature.properties.nama}</strong><br>
        <button onclick="laporkanKerusakan(${layer.getBounds().getCenter().lat}, ${layer.getBounds().getCenter().lng})"
            class="mt-2 bg-red-600 text-white px-2 py-1 rounded hover:bg-red-700">
            Laporkan Kerusakan
        </button>
    `);
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
                // Points
                const filteredPoints = allPointFeatures.filter(function(f) {
                    const props = f.properties || {};
                    const nama = (props.nama || '').toLowerCase();
                    const unit = (props.unit || '').toLowerCase();
                    const jenis = (props.jenis_bang || '').toLowerCase();
                    const matchSearch = !search || nama.includes(search) || unit.includes(search) || jenis.includes(search);
                    const matchSub = !subcat || nama === subcat;
                    return matchSearch && matchSub;
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
                            html: `<i class=\"fas ${matchedIcon} fa-lg\" style=\"color: ${iconColor}; background-color: white; padding: 4px; border-radius: 30%; box-shadow: 0 0 4px rgba(0,0,0,0.2); display: inline-block; line-height: 1;\"></i>`,
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
                        const foto = feature.properties?.dokumentasi;

                        let popupContent = `<strong>${nama}</strong><br>${jenis}<br>${unit}<br>`;

                        if (foto) {
                            popupContent += `<img src="${foto}" alt="Foto Dokumentasi" style="max-width: 200px; border-radius: 8px; margin-top: 10px;">`;
                        }

                        layer.bindPopup(popupContent);
                    }

                }).addTo(buildingPoints);
                map.addLayer(buildingPoints);
                // Polygons
                const filteredPolygons = allPolygonFeatures.filter(function(f) {
                    const props = f.properties || {};
                    const nama = (props.nama || props.name) ? (props.nama || props.name) : 'Lainnya';
                    const matchSearch = !search || nama.toLowerCase().includes(search);
                    const matchSub = !subcat || nama === subcat;
                    return matchSearch && matchSub;
                });
                L.geoJSON({
                    type: 'FeatureCollection',
                    features: filteredPolygons
                }, {
                    style: function(feature) {
                        const nama = feature.properties && (feature.properties.nama || feature.properties.name) ? (feature.properties.nama || feature.properties.name) : 'Lainnya';
                        return {
                            color: colorMap[nama] || '#007BFF',
                            weight: 2,
                            fillOpacity: 0.6
                        };
                    },
                    onEachFeature: function(feature, layer) {
                        const nama = feature.properties?.nama || 'Tidak diketahui';
                        const jmlLantai = feature.properties?.jml_lantai ? `<br>Jumlah Lantai: ${feature.properties.jml_lantai}` : '';
                        layer.bindPopup(`
        <strong>${nama}</strong>${jmlLantai}<br>
        <button onclick="laporkanKerusakan(${layer.getBounds().getCenter().lat}, ${layer.getBounds().getCenter().lng})"
            class="mt-2 bg-red-600 text-white px-2 py-1 rounded hover:bg-red-700">
            Laporkan Kerusakan
        </button>
    `);
                    }



                }).addTo(buildingPolygons);
                map.addLayer(buildingPolygons);
                // Jalan
                const filteredJalan = allJalanFeatures.filter(function(f) {
                    const props = f.properties || {};
                    const nama = (props.nama || '').toLowerCase();
                    const matchSearch = !search || nama.includes(search);
                    const matchSub = !subcat || nama === subcat;
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
        fetch('/polygon')
            .then(response => response.json())
            .then(data => {
                // Mapping manual warna berdasarkan nama bangunan
                colorMap = {
                    "Teknik": "#007BFF",
                    "Kedokteran, Kesehatan Masyarakat, dan Keperawatan": "#28a745",
                    "Perpustakaan": "#fdcb2c",
                    "Psikologi": "#adb5bd",
                    "Ekonomika dan Bisnis": "#007BFF",
                    "Ilmu Budaya": "#28a745",
                    "Sekolah Vokasi": "#fdcb2c",
                    "Hukum": "#adb5bd",
                    "Gelanggang Inovasi dan Kreativitas": "#007BFF",
                    "Kehutanan": "#28a745",
                    "Teknologi Pertanian": "#fdcb2c",
                    "Pertanian": "#adb5bd",
                    "Peternakan": "#28a745",
                    "Pascasarjana": "#fdcb2c",
                    "Balairung": "#adb5bd",
                    "Perikanan": "#007BFF",
                    "Kedokteran Hewan": "#28a745",
                    "MIPA": "#fdcb2c",
                    "Filsafat": "#adb5bd",
                    "Biologi": "#adb5bd",
                    "Dalam proses pembangunan": "#007BFF",
                    "Historis": "#28a745",
                    "Geografi": "#fdcb2c",
                    "Asrama Darmaputera Santren": "#adb5bd",
                    "Asrama Kinanti": "#adb5bd",
                    "Asrama Ratnaningsih": "#adb5bd",
                    "Masjid Kampus": "#007BFF",
                    "Wisma MM UGM": "#28a745",
                    "Fasilitas Kerohanian UGM": "#fdcb2c",
                    "Lapangan Tennis": "#adb5bd",
                    "Stadion Olahraga": "#adb5bd",
                    "Komplek Rumah Dinas UGM": "#adb5bd",
                    "PSLH": "#007BFF",
                    "Kedokteran Gigi": "#28a745",
                    "Ilmu Sosial dan Politik": "#fdcb2c",
                    "Kantor Bank BNI Unit UGM": "#adb5bd",
                    "Kantor Bank BRI Unit UGM": "#adb5bd",
                    "Kantor Bank Mandiri Cabang UGM": "#adb5bd",
                    "Pusat Studi Kebijakan dan Kependudukan": "#adb5bd",
                    "Farmasi": "#adb5bd"
                };

                allPolygonFeatures = data.features || [];

                // Tambahkan nama yang belum ada di colorMap ke warna default (optional)
                const defaultColors = [
                    '#6f42c1', '#fd7e14', '#17a2b8', '#4c8a65',
                    '#e83e8c', '#0d6efd', '#ffc107', '#198754',
                    '#a78fbf', '#0dcaf0', '#ff6f61', '#8bc34a',
                    '#ff9800', '#635f30', '#9c27b0'
                ];
                let colorIdx = 0;

                allPolygonFeatures.forEach(f => {
                    const nama = f.properties && (f.properties.nama || f.properties.name) ? (f.properties.nama || f.properties.name) : 'Lainnya';
                    if (!colorMap[nama]) {
                        colorMap[nama] = defaultColors[colorIdx % defaultColors.length];
                        colorIdx++;
                    }
                });

                if (document.getElementById('categoryFilter').value === 'polygon') updateSubCategoryOptions();

                // Generate legend dinamis
                const polygonLegend = document.getElementById('polygonLegend');
                if (polygonLegend) {
                    let html = '<div class="mt-1">';
                    Object.entries(colorMap).forEach(([nama, color]) => {
                        html += `<div class='flex items-center mb-1'><span style='display:inline-block;width:18px;height:12px;background:${color};border:1px solid #888;margin-right:6px;'></span>${nama}</div>`;
                    });
                    html += '</div>';
                    polygonLegend.innerHTML += html;
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
            updateSubCategoryOptions();
            unifiedFilterAndDisplay();
        });
        document.getElementById('subCategoryFilter').addEventListener('change', unifiedFilterAndDisplay);
        // Initial population
        updateSubCategoryOptions();
        unifiedFilterAndDisplay();

        function laporkanKerusakan(lat, lng) {
            const url = `/report?lat=${lat}&lng=${lng}`;
            window.location.href = url;
        }
    </script>
</body>

</html>