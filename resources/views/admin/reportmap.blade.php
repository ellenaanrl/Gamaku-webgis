<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Report Map - Gamaku WebGIS</title>    <!-- Custom Fonts -->
    <link href="{{ asset('css/fonts.css') }}" rel="stylesheet">

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

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <!-- Leaflet Draw CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />

    <style>
        body {
            font-family: 'Gama Sans', sans-serif;
        }
        #map {
            height: calc(100vh - 64px);
            width: 100%;
        }
    </style>
</head>
<body class="antialiased bg-gray-50 flex flex-col min-h-screen">
    <!-- Wrapper container -->
    <div class="flex-grow">
        <!-- Header/Navigation -->
        <nav class="bg-[#083d62] shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center space-x-3">
                        <img src="{{ asset('images/logo kuningg.png') }}" alt="Gamaku Logo" class="h-8 w-auto object-contain"/>
                        <h1 class="text-2xl font-bold text-[#fdcb2c]">Peta Laporan Kerusakan</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('admin.map') }}" class="text-gray-300 hover:text-white active:text-[#fdcb2c] px-3 py-2 text-sm font-medium">Peta Data Spasial</a>
                        <a href="{{ route('admin.reportmap') }}" class="text-[#fdcb2c] px-3 py-2 text-sm font-medium">Peta Lokasi Kerusakan</a>
                        <a href="{{ route('admin.dashboard') }}" class="text-gray-300 hover:text-white active:text-[#fdcb2c] px-3 py-2 text-sm font-medium">Dashboard</a>
                        <a href="/admin/reports" class="text-gray-300 hover:text-white active:text-[#fdcb2c] px-3 py-2 text-sm font-medium">Laporan Kerusakan</a>
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
        <main>
            <div id="map"></div>
        </main>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <!-- Leaflet Draw -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>

    <script>
        // Initialize the map centered on UGM
        var map = L.map('map').setView([-7.7713847, 110.3753189], 16);

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // --- Display damage report points ---
        fetch('/admin/reports/geojson')
            .then(res => res.json())
            .then(data => {
                console.log('Damage report geojson:', data);
                let hasPoint = false;
                // Try as GeoJSON
                if (data && data.type === 'FeatureCollection' && Array.isArray(data.features)) {
                    hasPoint = data.features.some(f => f.geometry && f.geometry.type === 'Point');
                    L.geoJSON(data, {
                        pointToLayer: function(feature, latlng) {
                            return L.marker(latlng, {
                                icon: L.divIcon({
                                    html: '<i class="fas fa-exclamation-triangle fa-lg" style="color:#dc3545;background:white;padding:4px;border-radius:50%;box-shadow:0 0 4px rgba(0,0,0,0.2);"></i>',
                                    className: '',
                                    iconSize: [28, 28],
                                    iconAnchor: [14, 14],
                                    popupAnchor: [0, -14]
                                })
                            });
                        },
                        onEachFeature: function(feature, layer) {
                            let popup = `<strong>Lokasi Kerusakan</strong>`;
                            if (feature.properties) {
                                if (feature.properties.reporter_name) popup += `<br>Pelapor: ${feature.properties.reporter_name}`;
                                if (feature.properties.category) popup += `<br>Kategori: ${feature.properties.category}`;
                                if (feature.properties.subcategory) popup += `<br>Subkategori: ${feature.properties.subcategory}`;
                                if (feature.properties.description) popup += `<br>${feature.properties.description}`;
                                if (feature.properties.created_at) popup += `<br><small>${feature.properties.created_at}</small>`;
                            }
                            layer.bindPopup(popup);
                        }
                    }).addTo(map);
                }
                // Fallback: array of objects with lat/lng
                else if (Array.isArray(data)) {
                    data.forEach(function(item) {
                        if (item.latitude && item.longitude) {
                            hasPoint = true;
                            L.marker([item.latitude, item.longitude], {
                                icon: L.divIcon({
                                    html: '<i class="fas fa-exclamation-triangle fa-lg" style="color:#dc3545;background:white;padding:4px;border-radius:50%;box-shadow:0 0 4px rgba(0,0,0,0.2);"></i>',
                                    className: '',
                                    iconSize: [28, 28],
                                    iconAnchor: [14, 14],
                                    popupAnchor: [0, -14]
                                })
                            })
                            .bindPopup(`<strong>Lokasi Kerusakan</strong><br>${item.description || ''}<br><small>${item.created_at || ''}</small>`)
                            .addTo(map);
                        }
                    });
                }
                if (!hasPoint) {
                    console.warn('No damage report points found in geojson or array.');
                    // Show a message overlay on the map
                    var msgDiv = document.createElement('div');
                    msgDiv.innerHTML = '<div style="position:absolute;top:80px;left:50%;transform:translateX(-50%);z-index:1000;background:rgba(255,255,255,0.95);padding:16px 32px;border-radius:8px;box-shadow:0 2px 8px #0002;font-size:1.1rem;color:#dc3545;">Tidak ada lokasi laporan kerusakan ditemukan.<br>Periksa data atau endpoint GeoJSON.</div>';
                    document.body.appendChild(msgDiv);
                    setTimeout(function(){ msgDiv.remove(); }, 6000);
                }
            })
            .catch(err => {
                console.error('Error fetching damage report geojson:', err);
            });

        // Initialize draw control
        var drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);

        var drawControl = new L.Control.Draw({
            draw: {
                polygon: true,
                polyline: true,
                rectangle: true,
                circle: false,
                circlemarker: false,
                marker: true
            },
            edit: {
                featureGroup: drawnItems
            }
        });
        map.addControl(drawControl);

        // Handle drawn items
        map.on('draw:created', function(e) {
            var layer = e.layer;
            drawnItems.addLayer(layer);

            // Get GeoJSON representation
            var geoJson = layer.toGeoJSON();
            console.log(geoJson);
        });
    </script>
</body>
</html>
