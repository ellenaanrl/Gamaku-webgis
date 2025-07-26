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
    }
  </style>
</head>

<body class="bg-gray-50 flex flex-col min-h-screen">
  <nav class="bg-[#083d62] shadow-sm">
    <div class="max-w-7xl mx-auto px-4">
      <div class="flex justify-between h-16">
        <div class="flex items-center space-x-3">
          <img src="{{ asset('images/logo kuningg.png') }}" alt="Gamaku Logo" class="h-8 object-contain" />
          <h1 class="text-2xl font-bold text-[#fdcb2c]">Peta Data Spasial</h1>
        </div>
        <div class="flex items-center space-x-4">
          <a href="{{ route('admin.map') }}" class="text-[#fdcb2c] px-3 py-2 text-sm font-medium">Peta Data Spasial</a>
          <a href="{{ route('admin.reportmap') }}" class="text-gray-300 hover:text-white px-3 py-2 text-sm font-medium">Peta Lokasi Kerusakan</a>
          <a href="/admin/reports" class="text-gray-300 hover:text-white px-3 py-2 text-sm font-medium">Laporan Kerusakan</a>
          <div class="flex items-center bg-[#0a4a75] rounded-lg px-3 py-2">
            <i class="fas fa-user-shield text-white mr-2"></i>
            <span class="text-white text-sm font-medium">{{ Auth::user()->name }}</span>
          </div>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="bg-red-600 text-white hover:bg-red-700 px-4 py-2 rounded-md text-sm font-medium">
              Logout
            </button>
          </form>
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