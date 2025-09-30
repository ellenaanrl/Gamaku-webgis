<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Report Map - Gamaku WebGIS</title>
  <!-- Custom Fonts -->
  <link href="{{ asset('css/fonts.css') }}" rel="stylesheet">
  <!-- Font Awesome CDN -->
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

  <!-- Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <!-- Leaflet Draw CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />
  <!-- Leaflet MarkerCluster CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />

  <style>
    body {
      font-family: 'Gama Sans', sans-serif;
    }

    #map {
      height: calc(100vh - 64px);
      width: 100%;
      z-index: 0 !important;
    }

    .custom-div-icon {
      background: transparent;
      border: none;
    }

    .custom-div-icon div {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 25px;
      height: 25px;
      transition: transform 0.2s;
    }

    .custom-div-icon div:hover {
      transform: scale(1.1);
    }

    .custom-div-icon i {
      font-size: 14px;
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
          <a href="{{ route('admin.reportmap') }}" class="block text-[#fdcb2c] hover:bg-[#0a4a75] px-3 py-2 text-sm font-medium"><i class="fa-solid fa-map-location-dot"></i> Peta Lokasi Kerusakan</a>
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
              <a href="{{ route('admin.map') }}" class="block text-gray-300 hover:text-white px-3 py-2 text-sm font-medium">
                <i class="fa-solid fa-map mr-2"></i>Peta Data Spasial
              </a>
              <a href="{{ route('admin.reportmap') }}" class="block text-[#fdcb2c] hover:bg-[#0a4a75] px-3 py-2 text-sm font-medium">
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

  <main>
    <div id="map"></div>
  </main>
  </div>

  <!-- Leaflet JS -->
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <!-- Leaflet Draw -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
  <!-- Leaflet MarkerCluster JS -->
  <script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>

  <script>
    var map = L.map('map').setView([-7.7713847, 110.3753189], 16);

    L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
      attribution: 'Tiles © Esri'
    }).addTo(map);

    // Marker Cluster untuk laporan yang belum completed
    var markersCluster = L.markerClusterGroup();

    // Custom icons
    const pendingIcon = L.divIcon({
      html: '<div class="bg-white p-1 rounded-full shadow-lg"><i class="fas fa-exclamation-triangle text-red-500"></i></div>',
      className: 'custom-div-icon',
      iconSize: [25, 25],
      iconAnchor: [12, 12]
    });

    const completedIcon = L.divIcon({
      html: '<div class="bg-white p-1 rounded-full shadow-lg"><i class="fa-solid fa-circle-check text-green-500"></i></div>',
      className: 'custom-div-icon',
      iconSize: [25, 25],
      iconAnchor: [12, 12]
    });

    // Batas AOI UGM
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
      });

    // Data laporan kerusakan
    fetch('/admin/reports/geojson')
      .then(response => response.json())
      .then(data => {
        if (data && data.type === 'FeatureCollection') {
          L.geoJSON(data, {
            pointToLayer: function(feature, latlng) {
              const status = feature.properties.status;
              const icon = status === 'completed' ? completedIcon : pendingIcon;

              const marker = L.marker(latlng, {
                icon: icon
              });

              // Pop-up
              const props = feature.properties;
              marker.bindPopup(`
              <div class="p-2">
                <h3 class="font-bold">${props.category}</h3>
                <p class="text-sm text-gray-600">${props.subcategory}</p>
                <p class="text-sm">${props.description}</p>
                <p class="text-sm mt-2">
                  <span class="font-semibold">Status:</span> 
                  <span class="${props.status === 'completed' ? 'text-green-600' : 'text-red-600'}">
                    ${props.status}
                  </span>
                </p>
              </div>
            `);

              // Masukkan ke cluster kalau belum completed
              if (status !== 'completed') {
                markersCluster.addLayer(marker);
                return null; // jangan tambahkan ke map langsung
              } else {
                return marker; // completed langsung ditampilkan di map
              }
            }
          }).addTo(map);

          // Tambahkan cluster ke map
          map.addLayer(markersCluster);
        }
      })
      .catch(err => {
        console.error('Error fetching damage report geojson:', err);
      });
  </script>
</body>

</html>