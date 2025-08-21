<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin Map - Gamaku WebGIS</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="{{ asset('css/fonts.css') }}" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
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

    #map {
      height: calc(100vh - 64px);
      z-index: 0 !important;
    }
  </style>
</head>

<body class="bg-gray-50 flex flex-col min-h-screen relative">
  <nav class="bg-[#083d62] shadow-sm sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        <!-- Logo -->
        <div class="flex items-center space-x-2 min-w-0 flex-shrink-0">
          <img src="{{ asset('images/logo kuningg.png') }}" alt="Gamaku Logo" class="h-8 sm:h-12 w-auto object-contain flex-shrink-0" />
          <h1 class="text-lg sm:text-2xl font-bold text-[#fdcb2c] truncate">Gamaku</h1>
        </div>

        <!-- Menu desktop -->
        <div class="hidden lg:flex items-center space-x-4">
          <a href="{{ route('admin.map') }}" class="text-[#fdcb2c] px-3 py-2 text-sm font-medium"><i class="fa-solid fa-map"></i> Peta Data Spasial</a>
          <a href="{{ route('admin.reportmap') }}" class="text-gray-300 hover:text-white px-3 py-2 text-sm font-medium"><i class="fa-solid fa-map-location-dot"></i> Peta Lokasi Kerusakan</a>
          <a href="/admin/reports" class="text-gray-300 hover:text-white px-3 py-2 text-sm font-medium"><i class="fa-solid fa-clipboard-list"></i> Laporan Kerusakan</a>
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
              <a href="{{ route('admin.map') }}" class="block text-[#fdcb2c] hover:bg-[#0a4a75] px-3 py-2 text-sm font-medium">
                <i class="fa-solid fa-map mr-2"></i>Peta Data Spasial
              </a>
              <a href="{{ route('admin.reportmap') }}" class="block text-gray-300 hover:text-white px-3 py-2 text-sm font-medium">
                <i class="fa-solid fa-map-location-dot mr-2"></i>Peta Lokasi Kerusakan
              </a>
              <a href="/admin/reports" class="block text-gray-300 hover:text-white px-3 py-2 text-sm font-medium">
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


  <main class="flex-grow">
    <main class="flex-grow">
      <div id="map"></div>
    </main>
  </main>

  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>

  <script>
    const map = L.map('map').setView([-7.7713847, 110.3753189], 16);

    const osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '© OpenStreetMap contributors'
    });

    const esri = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
      attribution: 'Tiles © Esri'
    }).addTo(map); // Set Esri as default

    const googleHybrid = L.tileLayer('http://{s}.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
      maxZoom: 20,
      subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
      attribution: '© Google'
    });

    const baseMaps = {
      "OpenStreetMap": osm,
      "Esri World Imagery": esri,
      "Google Hybrid": googleHybrid
    };

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

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

    L.control.layers(baseMaps, null, {
      collapsed: false
    }).addTo(map);

    const drawnItems = new L.FeatureGroup();
    map.addLayer(drawnItems);

    const drawControl = new L.Control.Draw({
      draw: {
        polygon: true,
        polyline: true,
        rectangle: true,
        circle: false,
        marker: true
      },
      edit: {
        featureGroup: drawnItems
      }
    });
    map.addControl(drawControl);

    const addAndDraw = (geojsonData) => {
      const layer = L.geoJSON(geojsonData, {
        style: function(feature) {
          if (feature.geometry.type === "Polygon") {
            return {
              color: "#fd7e14",
              fillColor: "#ffd966",
              weight: 1,
              fillOpacity: 0.5
            };
          } else if (feature.geometry.type === "LineString") {
            return {
              color: "#ff0000",
              weight: 3,
              opacity: 0.8
            };
          }
        },
        onEachFeature: (feature, layer) => {
          layer.bindPopup(feature.properties.nama || feature.properties.nama || "Tanpa Nama");
          drawnItems.addLayer(layer);
        }
      });
      return layer;
    };

    fetch('/admin/data/points')
      .then(res => res.json())
      .then(data => addAndDraw(data).addTo(map));

    fetch('/admin/data/jalan')
      .then(res => res.json())
      .then(data => {
        const jalanLayer = L.geoJSON(data, {
          style: {
            color: "#ff0000",
            weight: 3,
            opacity: 0.8
          },
          onEachFeature: (feature, layer) => {
            layer.bindPopup(feature.properties.nama || "Jalan");
            drawnItems.addLayer(layer);
          }
        }).addTo(map);
      });

    fetch('/admin/data/polygons')
      .then(res => res.json())
      .then(data => {
        const polygonLayer = L.geoJSON(data, {
          style: {
            color: "#fd7e14",
            fillColor: "#ffd966", // Warna isi polygon
            weight: 1,
            fillOpacity: 0.5
          },
          onEachFeature: (feature, layer) => {
            layer.bindPopup(feature.properties.nama || "Bangunan");
            drawnItems.addLayer(layer);
          }
        }).addTo(map);
      });


    map.on('draw:created', function(e) {
      const layer = e.layer;
      const geojson = layer.toGeoJSON();
      const geometry = geojson.geometry;

      let url = '';
      let featureData = {};

      if (geometry.type === "Point") {
        url = '/admin/store-point';
        const nama = prompt("Masukkan Nama Titik:", "Titik Baru");
        const unit = prompt("Masukkan Unit:", "-");
        const jenis_bang = prompt("Masukkan Jenis Bangunan:", "-");

        if (!nama || !unit || !jenis_bang) {
          alert('Semua field harus diisi!');
          return;
        }

        featureData = {
          type: "Feature",
          geometry: geometry,
          properties: {
            nama: nama,
            unit: unit,
            jenis_bang: jenis_bang
          }
        };
      } else if (geometry.type === "Polygon") {
        url = '/admin/store-polygon';
        const nama = prompt("Masukkan Nama Bangunan:", "Bangunan Baru");
        if (!nama) {
          alert('Nama bangunan harus diisi!');
          return;
        }

        featureData = {
          type: "Feature",
          geometry: geometry,
          properties: {
            nama: nama
          }
        };
      } else if (geometry.type === "LineString") {
        url = '/admin/store-jalan';
        const nama = prompt("Masukkan Nama Jalan:", "Jalan Baru");
        if (!nama) {
          alert('Nama jalan harus diisi!');
          return;
        }

        featureData = {
          type: "Feature",
          geometry: geometry,
          properties: {
            nama: nama
          }
        };
      } else {
        return alert('Tipe geometri tidak didukung.');
      }

      fetch(url, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          body: JSON.stringify(featureData)
        })
        .then(res => {
          if (!res.ok) {
            throw new Error('Network response was not ok');
          }
          return res.json();
        })
        .then(data => {
          alert(data.message || 'Data berhasil disimpan');
          // Refresh the map data without full page reload
          drawnItems.clearLayers();
          fetch('/admin/data/points').then(res => res.json()).then(data => addAndDraw(data));
          fetch('/admin/data/jalan').then(res => res.json()).then(data => addAndDraw(data));
          fetch('/admin/data/polygons').then(res => res.json()).then(data => addAndDraw(data));
        })
        .catch(err => {
          console.error('Error:', err);
          alert('Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
        });

      drawnItems.addLayer(layer);
    });

    map.on('draw:edited', function(e) {
      e.layers.eachLayer(function(layer) {
        const geojson = layer.toGeoJSON().geometry;
        const props = layer.feature.properties;
        const id = layer.feature.id;

        if (!id) {
          alert("ID tidak ditemukan, tidak bisa update.");
          return;
        }

        fetch(`/admin/update-point/${id}`, {
            method: 'PUT',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({
              geometry: geojson,
              properties: props
            })
          })
          .then(res => res.json())
          .then(data => {
            alert(data.message || "Titik berhasil diupdate!");
          })
          .catch(err => {
            alert("Gagal update titik: " + err);
          });
      });
    });


    map.on('draw:deleted', function(e) {
      e.layers.eachLayer(function(layer) {
        const id = layer.feature.id;

        fetch(`/admin/delete-point/${id}`, {
          method: 'DELETE',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
          }
        }).then(res => res.json()).then(data => {
          alert(data.message);
        });
      });
    });
  </script>
</body>

</html>