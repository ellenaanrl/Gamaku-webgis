<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - Gamaku WebGIS</title>

    <!-- Custom Fonts -->
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
</head>
<body class="antialiased bg-gray-50 font-sans">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-[#083d62] shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center space-x-2">
                        @if(file_exists(public_path('images/logo kuningg.png')))
                            <img src="{{ asset('images/logo kuningg.png') }}" alt="Gamaku Logo" class="h-12 w-auto object-contain"/>
                        @endif
                        <h1 class="text-2xl font-bold text-[#fdcb2c]">Gamaku WebGIS</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="/" class="text-gray-300 hover:text-white px-3 py-2 text-sm font-medium">Beranda</a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Register Section -->
        <div class="flex min-h-[calc(100vh-4rem)] bg-cover bg-center" style="background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('/UGM.jpeg')">
            <div class="flex-1 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
                <div class="sm:mx-auto sm:w-full sm:max-w-md">
                    <h2 class="text-center text-3xl font-bold tracking-tight text-white">Daftar Akun Baru</h2>
                    <p class="mt-2 text-center text-sm text-gray-200">
                        Atau
                        <a href="{{ route('login') }}" class="font-medium text-[#fdcb2c] hover:text-yellow-400">masuk ke akun</a>
                    </p>
                </div>

                <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
                    <div class="backdrop-blur-md bg-white/90 py-8 px-4 shadow-lg sm:rounded-lg sm:px-10 border border-white/20">
                        <form method="POST" action="{{ route('register') }}" class="space-y-6">
                            @csrf

                            @if ($errors->any())
                                <div class="bg-red-500/10 text-red-400 p-4 rounded-md border border-red-500/20">
                                    <ul class="list-disc list-inside">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-800">Nama Lengkap</label>
                                <input id="name" name="name" type="text" :value="old('name')" required autofocus autocomplete="name"
                                    class="block w-full appearance-none rounded-md bg-white/50 border border-gray-300/50 px-3 py-2 
                                    placeholder-gray-400 shadow-sm focus:border-[#083d62] focus:outline-none focus:ring-[#083d62] sm:text-sm">
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-800">Email</label>
                                <input id="email" name="email" type="email" :value="old('email')" required autocomplete="username"
                                    class="block w-full appearance-none rounded-md bg-white/50 border border-gray-300/50 px-3 py-2 
                                    placeholder-gray-400 shadow-sm focus:border-[#083d62] focus:outline-none focus:ring-[#083d62] sm:text-sm">
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-800">Password</label>
                                <input id="password" name="password" type="password" required autocomplete="new-password"
                                    class="block w-full appearance-none rounded-md bg-white/50 border border-gray-300/50 px-3 py-2 
                                    placeholder-gray-400 shadow-sm focus:border-[#083d62] focus:outline-none focus:ring-[#083d62] sm:text-sm">
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-800">Konfirmasi Password</label>
                                <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                                    class="block w-full appearance-none rounded-md bg-white/50 border border-gray-300/50 px-3 py-2 
                                    placeholder-gray-400 shadow-sm focus:border-[#083d62] focus:outline-none focus:ring-[#083d62] sm:text-sm">
                            </div>

                            <div>
                                <button type="submit" 
                                    class="flex w-full justify-center rounded-md border border-transparent bg-[#083d62] py-3 px-4 
                                    text-sm font-medium text-white shadow-sm hover:bg-[#083d62]/90 focus:outline-none focus:ring-2 
                                    focus:ring-[#fdcb2c] focus:ring-offset-2 transition-all duration-200">
                                    Daftar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-gray-800">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <p class="text-sm text-gray-400">
                        © 2025 Gamaku WebGIS. All rights reserved.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
