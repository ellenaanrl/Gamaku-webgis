<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Dashboard - Gamaku WebGIS</title>    <!-- Custom Fonts -->
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

    <style>
        body {
            font-family: 'Gama Sans', sans-serif;
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
                        <img src="{{ asset('images/logo.png') }}" alt="Gamaku Logo" class="h-8 w-auto object-contain"/>
                        <h1 class="text-2xl font-bold text-white">Admin Dashboard</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('admin.map') }}" class="text-gray-300 hover:text-white active:text-[#fdcb2c] px-3 py-2 text-sm font-medium">Peta Data Spasial</a>
                        <a href="{{ route('admin.reportmap') }}" class="text-gray-300 hover:text-white active:text-[#fdcb2c] px-3 py-2 text-sm font-medium">Peta Lokasi Kerusakan</a>
                        <a href="{{ route('admin.dashboard') }}" class="text-[#fdcb2c] px-3 py-2 text-sm font-medium">Dashboard</a>
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
        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="px-4 py-6 sm:px-0">
                <div class="border-4 border-dashed border-gray-200 rounded-lg p-4">
                    <h2 class="text-2xl font-bold mb-4">Welcome, {{ Auth::user()->name }}!</h2>
                    <p class="mb-4">This is a protected admin-only dashboard. Only users with admin role can access this page.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="bg-white p-4 rounded-lg shadow">
                            <h3 class="font-bold text-lg mb-2">Quick Stats</h3>
                            <p>Example admin-only content.</p>
                        </div>
                        
                        <div class="bg-white p-4 rounded-lg shadow">
                            <h3 class="font-bold text-lg mb-2">Recent Activity</h3>
                            <p>More admin-only content here.</p>
                        </div>
                        
                        <div class="bg-white p-4 rounded-lg shadow">
                            <h3 class="font-bold text-lg mb-2">System Status</h3>
                            <p>Additional admin-only information.</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-gray-400 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            © 2025 Gamaku WebGIS. All rights reserved.
        </div>
    </footer>
</body>
</html>
