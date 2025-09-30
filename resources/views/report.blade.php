<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporkan Kerusakan - Gamaku WebGIS</title>
    <!-- Custom Fonts -->
    <link href="{{ asset('css/fonts.css') }}" rel="stylesheet">
    <!-- Font Awesome 6 Free -->
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
    <script src="https://unpkg.com/@turf/turf@6/turf.min.js"></script>

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
        <nav class="bg-[#083d62] shadow-sm sticky top-0 z-40">
            <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo and Title -->
                    <div class="flex items-center space-x-2 min-w-0 flex-shrink-0">
                        @if(file_exists(public_path('images/logo.png')))
                        <img src="{{ asset('images/logo.png') }}" alt="Gamaku Logo" class="h-8 sm:h-12 w-auto object-contain flex-shrink-0" />
                        @endif
                        <h1 class="text-lg sm:text-2xl font-bold text-[#fdcb2c] truncate">Gamaku</h1>
                    </div>

                    <div class="hidden lg:flex items-center space-x-4">
                        <a href="/" class="text-gray-300 hover:text-white active:text-[#fdcb2c] px-2 sm:px-3 py-1 sm:py-2 text-xs sm:text-sm font-medium flex items-center focus:outline-none">
                            <i class="fa-solid fa-house mr-1"></i> Beranda
                        </a>
                        <a href="/map" class="text-gray-300 hover:text-white active:text-[#fdcb2c] px-3 py-2 text-sm font-medium whitespace-nowrap">
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
                        <a href="/management" class="text-[#fdcb2c] px-3 py-2 text-sm font-medium whitespace-nowrap">
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
                                <a href="/" class="block text-gray-300 hover:bg-[#0a4a75] px-3 py-2 text-sm">
                                    <i class="fa-solid fa-house mr-2"></i>Beranda
                                </a>
                                <a href="/map" class="block text-gray-300 hover:text-white px-3 py-2 text-sm font-medium">
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
                                <a href="/management" class="block text-[#fdcb2c] hover:bg-[#0a4a75] px-3 py-2 text-sm font-medium">
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

                        <div> <label for="reporter_name" class="block font-medium">Nama Pelapor</label>
                            <input type="text" name="reporter_name" id="reporter_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-blue-600" required>
                        </div>

                        <div>
                            <label for="position" class="block font-medium">Jabatan/posisi</label>
                            <select name="position" id="position" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm text-blue-600" required>
                                <option value="">pilih jabatan/posisi</option>
                                <option value="Mahasiswa">Mahasiswa</option>
                                <option value="Dosen">Dosen</option>
                                <option value="Tenaga Kependidikan">Tenaga Kependidikan</option>
                                <option value="Alumni">Alumni</option>
                            </select>
                        </div>

                        <div>
                            <label for="department" class="block font-medium">Fakultas/Sekolah pelapor</label>
                            <select name="department" id="department" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm text-blue-600" required>
                                <option value="">pilih fakultas/sekolah</option>
                                <option value="Biologi">Biologi</option>
                                <option value="Ekonomika dan Bisnis">Ekonomika dan Bisnis</option>
                                <option value="Farmasi">Farmasi</option>
                                <option value="Filsafat">Filsafat</option>
                                <option value="Geografi">Geografi</option>
                                <option value="Hukum">Hukum</option>
                                <option value="Ilmu Budaya">Ilmu Budaya</option>
                                <option value="Ilmu Sosial dan Ilmu Politik">Ilmu Sosial dan Ilmu Politik</option>
                                <option value="Kedokteran Gigi">Kedokteran Gigi</option>
                                <option value="Kedokteran Hewan">Kedokteran Hewan</option>
                                <option value="Kedokteran, Kesehatan Masyarakat, dan Keperawatan">Kedokteran, Kesehatan Masyarakat, dan Keperawatan</option>
                                <option value="Kehutanan">Kehutanan</option>
                                <option value="Matematika dan Ilmu Pengetahuan Alam (FMIPA)">Matematika dan Ilmu Pengetahuan Alam (FMIPA)</option>
                                <option value="Pertanian">Pertanian</option>
                                <option value="Psikologi">Psikologi</option>
                                <option value="Teknik">Teknik</option>
                                <option value="Teknologi Pertanian">Teknologi Pertanian</option>
                                <option value="Sekolah Vokasi">Sekolah Vokasi</option>
                            </select>
                        </div>

                        <div>
                            <label for="phone" class="block font-medium">No telepon (whatsapp)</label>
                            <input type="text" name="phone" id="phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-blue-600" required>
                        </div>

                        <div>
                            <label for="email" class="block font-medium">Email</label>
                            <input type="email" name="email" id="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-blue-600" required>
                        </div>

                        <div class="mb-4">
                            <label for="category" class="block font-medium">Kategori Kerusakan</label>
                            <select name="category" id="category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm text-blue-600" required>
                                <option value="">Pilih kategori</option>
                                <option value="jalan">Jalan</option>
                                <option value="bangunan">Bangunan</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="subcategory" class="block font-medium">Subkategori Kerusakan</label>
                            <select name="subcategory" id="subcategory" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm text-blue-600" required>
                                <option value="">Pilih subkategori</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="impact" class="block font-medium">Dampak Kerusakan Yang Ditimbulkan</label>
                            <select name="impact" id="impact" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm text-blue-600" required>
                                <option value="">Pilih dampak kerusakan</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="floor" class="block font-medium">Lantai lokasi kerusakan</label>
                            <select name="floor" id="floor" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm text-blue-600" required>
                                <option value="">Pilih lantai</option>
                                <option value="jalan">jalan</option>
                                <option value="basement">basement</option>
                                <option value="lantai 1">lantai 1</option>
                                <option value="lantai 2">lantai 2</option>
                                <option value="lantai 3">lantai 3</option>
                                <option value="lantai 4">lantai 4</option>
                                <option value="lantai 5">lantai 5</option>
                                <option value="lantai 6">lantai 6</option>
                                <option value="lantai 7">lantai 7</option>
                                <option value="lantai 8">lantai 8</option>
                                <option value="lantai 9">lantai 9</option>
                                <option value="lantai 10">lantai 10</option>
                                <option value="lantai 11">lantai 11</option>
                            </select>
                        </div>


                        <div>
                            <label for="kategori" class="block font-medium">Deskripsi Kerusakan</label>
                            <textarea name="description" id="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-blue-600" required></textarea>
                        </div>

                        <div>
                            <label for="kategori" class="block font-medium mb-2">Lokasi Kerusakan</label>
                            <button id="locateButton" type="button" class="border border-blue-500 text-blue-600 rounded px-2 py-1 bg-white hover:bg-blue-50 mb-3 text-xs">
                                <i class="fa-solid fa-location-crosshairs text-blue-600"></i>
                                <span class="text-blue-600">Gunakan Lokasi Saya</span>
                            </button>
                            <div id="map" class="rounded-lg border border-gray-300 mb-2"></div>
                            <!-- <input type="hidden" name="latitude" id="latitude" required>
                            <input type="hidden" name="longitude" id="longitude" required> -->
                            <p class="text-sm text-gray-500">Klik pada peta untuk menandai lokasi kerusakan</p>
                        </div>

                        <div class="mt-4 text-sm text-gray-700">
                            <strong>Lokasi Kerusakan:</strong>
                            <span id="lokasi" class="text-blue-600">Belum ditentukan</span>
                            <input type="hidden" name="lokasi" id="lokasi-input" value="">
                            <input type="text" name="latitude" id="latitude" value="{{ request('lat') }}" hidden>
                            <input type="text" name="longitude" id="longitude" value="{{ request('lng') }}" hidden>
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
                    <p class="text-xs sm:text-base text-gray-400">
                        © Ellena Nurlaila sebagai syarat Proyek Akhir (PA) 2025, serta dibimbing oleh Ari Cahyono, S.Si., Msc.
                    </p>
                    <p class="text-xs sm:text-base text-gray-400">
                        Prodi Sistem Informasi Geografis
                    </p>
                    <p class="text-xs sm:text-base text-gray-400">
                        Departemen Teknologi Kebumian
                    </p>
                    <p class="text-xs sm:text-base text-gray-400">
                        Sekolah Vokasi
                    </p>
                    <p class="text-xs sm:text-base text-gray-400">
                        Universitas Gadjah Mada
                    </p>
                </div>
            </div>
        </footer>
    </div>

    <script>
        // Initialize the map
        var map = L.map('map').setView([-7.77086217621573, 110.37913568656498], 13);
        var marker = null;

        const initLat = parseFloat("{{ request('lat') }}");
        const initLng = parseFloat("{{ request('lng') }}");

        if (!isNaN(initLat) && !isNaN(initLng)) {
            const initialLatLng = L.latLng(initLat, initLng);
            map.setView(initialLatLng, 18); // zoom in

            marker = L.marker(initialLatLng).addTo(map)
                .bindPopup("Lokasi Adanya Kerusakan").openPopup();

            document.getElementById('latitude').value = initLat;
            document.getElementById('longitude').value = initLng;
        }


        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            maxZoom: 19,
            attribution: 'Tiles © Esri'
        }).addTo(map);

        // Handle map clicks
        map.on('click', function(e) {
            if (marker) {
                map.removeLayer(marker);
            }
            marker = L.marker(e.latlng).addTo(map);

            // Update hidden form fields
            document.getElementById('latitude').value = e.latlng.lat;
            document.getElementById('longitude').value = e.latlng.lng;
        });
        const subcategoryOptions = {
            jalan: [{
                    value: 'permukaan',
                    text: 'Permukaan: retak, lubang, bergelombang atau tidak rata'
                },
                {
                    value: 'marka_rambu',
                    text: 'Marka dan Rambu: pudar, hilang, rusak'
                },
                {
                    value: 'trotoar',
                    text: 'Trotoar: rusak, tidak ramah disabilitas'
                },
            ],
            bangunan: [{
                    value: 'struktural',
                    text: 'Struktural: dinding, atap, lantai'
                },
                {
                    value: 'non_struktural',
                    text: 'Non-struktural: pintu, jendela, plafon'
                },
                {
                    value: 'instalasi',
                    text: 'Instalasi: listrik, lampu, saklar, kloset, AC, keran air'
                },
                {
                    value: 'aksesibilitas',
                    text: 'Aksesibilitas: lift, toilet difabel, jalur kursi roda'
                },
            ],
        };

        const impactOptions = {
            jalan: {
                permukaan: [{
                        value: 'kecelakaan',
                        text: 'Risiko kecelakaan lalu lintas'
                    },
                    {
                        value: 'kendaraan_rusak',
                        text: 'Potensi kerusakan kendaraan'
                    },
                    {
                        value: 'macet',
                        text: 'Kemacetan lalu lintas'
                    },
                    {
                        value: 'genangan',
                        text: 'Genangan air saat hujan'
                    }
                ],
                marka_rambu: [{
                        value: 'navigasi_sulit',
                        text: 'Kesulitan navigasi pengguna jalan'
                    },
                    {
                        value: 'keselamatan',
                        text: 'Risiko keselamatan lalu lintas'
                    },
                    {
                        value: 'kebingungan',
                        text: 'Kebingungan pengguna jalan'
                    }
                ],
                trotoar: [{
                        value: 'pejalan_terganggu',
                        text: 'Gangguan mobilitas pejalan kaki'
                    },
                    {
                        value: 'disabilitas_terhambat',
                        text: 'Hambatan akses pengguna disabilitas'
                    },
                    {
                        value: 'keselamatan_pejalan',
                        text: 'Risiko keselamatan pejalan kaki'
                    }
                ]
            },
            bangunan: {
                struktural: [{
                        value: 'roboh',
                        text: 'Risiko roboh/runtuh'
                    },
                    {
                        value: 'keselamatan_penghuni',
                        text: 'Bahaya keselamatan penghuni'
                    },
                    {
                        value: 'kerusakan_aset',
                        text: 'Kerusakan aset/properti'
                    }
                ],
                non_struktural: [{
                        value: 'fungsi_terganggu',
                        text: 'Gangguan fungsi ruangan'
                    },
                    {
                        value: 'kenyamanan',
                        text: 'Ketidaknyamanan pengguna'
                    },
                    {
                        value: 'estetika',
                        text: 'Penurunan nilai estetika'
                    }
                ],
                instalasi: [{
                        value: 'aktivitas_terganggu',
                        text: 'Gangguan aktivitas sehari-hari'
                    },
                    {
                        value: 'kelistrikan',
                        text: 'Risiko kelistrikan'
                    },
                    {
                        value: 'kebocoran',
                        text: 'Kebocoran air/gas'
                    },
                    {
                        value: 'pemborosan',
                        text: 'Pemborosan energi/sumber daya'
                    }
                ],
                aksesibilitas: [{
                        value: 'akses_terbatas',
                        text: 'Keterbatasan akses'
                    },
                    {
                        value: 'evakuasi',
                        text: 'Hambatan jalur evakuasi'
                    },
                    {
                        value: 'diskriminasi',
                        text: 'Diskriminasi pengguna disabilitas'
                    }
                ]
            }
        };

        document.getElementById('category').addEventListener('change', function() {
            const selectedCategory = this.value;
            const subcategorySelect = document.getElementById('subcategory');
            const impactSelect = document.getElementById('impact');

            // Clear both subcategory and impact options
            subcategorySelect.innerHTML = '<option value="">Pilih subkategori</option>';
            impactSelect.innerHTML = '<option value="">Pilih dampak kerusakan</option>';

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

        document.getElementById('subcategory').addEventListener('change', function() {
            const selectedCategory = document.getElementById('category').value;
            const selectedSubcategory = this.value;
            const impactSelect = document.getElementById('impact');

            // Clear impact options
            impactSelect.innerHTML = '<option value="">Pilih dampak kerusakan</option>';

            // Populate impact options based on selected category and subcategory
            if (impactOptions[selectedCategory] &&
                impactOptions[selectedCategory][selectedSubcategory]) {
                impactOptions[selectedCategory][selectedSubcategory].forEach(option => {
                    const opt = document.createElement('option');
                    opt.value = option.value;
                    opt.textContent = option.text;
                    impactSelect.appendChild(opt);
                });
            }
        });
        
        let polygonGeoJSON = null; // Variabel global
        let jalanGeoJSON = null; // Variabel global untuk jalan

        // Color map for polygon names
        const polygonColorMap = {
            "Teknik": "#1976d2",
            "Kehutanan": "#43a047",
            "Universitas Gadjah Mada": "#fbc02d",
            "Filsafat": "#e53935",
            "Ilmu Sosial dan Politik": "#8e24aa",
            "Teknologi Pertanian": "#00897b",
            "Pertanian": "#d81b60",
            "Biologi": "#6d4c41",
            "Sekolah Vokasi": "#757575",
            "Hukum": "#d81b60",
            "Farmasi": "#dac6daff",
            "Kedokteran": "#1eaf97ff",
            "Peternakan": "#a5486aff",
            "Psikologi": "#d371bcff",
            "MIPA": "#d8e66aff",
            "Ekonomika dan Bisnis": "#245d0aff",
            "Ilmu Budaya": "#cf911dff",
            "Kedokteran Hewan": "#671932ff",
            "Geografi": "#b15131ff",
            "Kedokteran Gigi": "#2a0fbeff",
            "Balairung": "#103099ff",
            "Grha Sabha Pramana": "#1cae57ff",
            "Stadion": "#da4784ff",
            "Komplek Rumah Dinas UGM": "#3b2d47ff",
            "Bengkel": "#423f3fff",
            "Masjid": "#4fff5bff",
            "Gelanggang Inovasi dan Kreativitas": "#161313ff",
            "Fasilitas Kerohanian UGM": "#305c40ff",
            "Pascasarjana": "#12022eff",
            "Asrama Mahasiswa": "#7d570bff",
            "dalam proses pembangunan": "#e3e1b6ff",

        };

        // Helper to get color by name
        function getPolygonColor(nama) {
            for (const key in polygonColorMap) {
                if (nama && nama.toLowerCase().includes(key.toLowerCase())) {
                    return polygonColorMap[key];
                }
            }
            return "#ffd966"; // default color
        }

        fetch('/polygon')
            .then(res => res.json())
            .then(data => {
                polygonGeoJSON = data; // simpan polygon untuk analisis Turf.js

                const polygonLayer = L.geoJSON(data, {
                    style: function(feature) {
                        const color = getPolygonColor(feature.properties.nama);
                        return {
                            color: color,
                            fillColor: color,
                            weight: 1,
                            fillOpacity: 0.5
                        };
                    },
                }).addTo(map);
            });

        // Add legend control
        var legend = L.control({
            position: 'bottomright'
        });

        legend.onAdd = function(map) {
            // container utama
            var div = L.DomUtil.create('div', 'info legend bg-white rounded shadow p-2');
            div.style.maxWidth = '200px';

            // inject alpinejs
            div.innerHTML = `
        <div x-data="{ open: false }" class="text-sm">
            <button type="button" @click="open = !open" class="w-full flex items-center justify-between px-2 py-1 bg-gray-200 rounded text-gray-700 hover:bg-gray-300">
                <span>Legenda Bangunan</span>
                <i :class="open ? 'fa-solid fa-chevron-up' : 'fa-solid fa-chevron-down'"></i>
            </button>
            <div x-show="open" x-transition class="mt-2 max-h-40 overflow-y-auto">
                ${Object.entries(polygonColorMap).map(([name, color]) =>
                    `<div class="flex items-center mb-1">
                        <span style="background:${color};width:14px;height:14px;display:inline-block;margin-right:6px;border-radius:2px;"></span> 
                        <span>${name}</span>
                    </div>`
                ).join('')}
            </div>
        </div>
    `;
            return div;
        };
        legend.addTo(map);

        // Fetch and display jalan data
        fetch('/jalan')
            .then(res => res.json())
            .then(data => {
                jalanGeoJSON = data; // simpan jalan untuk analisis Turf.js

                const jalanLayer = L.geoJSON(data, {
                    style: {
                        color: "#970a0aff",
                        weight: 3,
                        opacity: 0.8
                    },
                    onEachFeature: (feature, layer) => {}
                }).addTo(map);
            });

        map.on('click', function(e) {
            if (marker) {
                map.removeLayer(marker);
            }

            marker = L.marker(e.latlng).addTo(map)
                .bindPopup("Lokasi Adanya Kerusakan").openPopup();

            document.getElementById('latitude').value = e.latlng.lat;
            document.getElementById('longitude').value = e.latlng.lng;

            // Cek apakah titik berada di dalam salah satu polygon
            const point = turf.point([e.latlng.lng, e.latlng.lat]);

            let lokasi = "Tidak berada di dalam bangunan manapun";

            if (polygonGeoJSON) {
                for (let feature of polygonGeoJSON.features) {
                    const polygon = feature;
                    if (turf.booleanPointInPolygon(point, polygon)) {
                        lokasi = feature.properties.nama || "Bangunan tanpa nama";
                        break;
                    }
                }
            }

            // Cek apakah titik berada di dekat salah satu jalan (buffer 20 meter)
            if (jalanGeoJSON) {
                for (let feature of jalanGeoJSON.features) {
                    const line = feature;
                    const buffered = turf.buffer(line, 0.02, {
                        units: 'kilometers'
                    }); // ~20 meter
                    if (turf.booleanPointInPolygon(point, buffered)) {
                        lokasi = feature.properties.nama || "Jalan tanpa nama";
                        break;
                    }
                }
            }

            document.getElementById('lokasi').innerText = lokasi;
            document.getElementById('lokasi-input').value = lokasi;
        });

        //Geolocation
        document.getElementById('locateButton').addEventListener('click', function() {
            if (!navigator.geolocation) {
                alert('Geolocation tidak didukung oleh browser Anda');
                return;
            }

            navigator.geolocation.getCurrentPosition(function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;

                // Atur view peta ke lokasi saat ini
                map.setView([lat, lng], 18);

                // Hapus marker lama jika ada
                if (marker) {
                    map.removeLayer(marker);
                }

                // Tambahkan marker lokasi saat ini
                marker = L.marker([lat, lng]).addTo(map)
                    .bindPopup("Lokasi Saya").openPopup();

                // Update input hidden latitude dan longitude
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;

            }, function() {
                alert('Tidak dapat mengambil lokasi Anda');
            });
        });
    </script>
</body>

</html>