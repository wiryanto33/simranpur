<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIMRANPUR - Korps Marinir TNI AL</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900 bg-white selection:bg-[#C8A84B] selection:text-white">
    <!-- Navbar -->
    <nav class="fixed inset-x-0 top-0 z-50 transition-all duration-300 bg-white/90 backdrop-blur-md shadow-sm">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <div class="flex items-center flex-shrink-0 gap-3">
                    <div class="flex items-center justify-center w-20 h-20">
                        <img src="{{ asset('logo/tank.png') }}" alt="Logo" class="object-contain w-20 h-20 drop-shadow-md">
                    </div>
                    <div>
                        <h1 class="text-xl font-bold leading-tight text-[#1B3A2D]">SIMRANPUR</h1>
                        <span class="text-xs font-semibold tracking-wider text-[#C8A84B] uppercase">Korps Marinir</span>
                    </div>
                </div>
                <div class="hidden md:ml-6 md:flex md:space-x-8">
                    <a href="#fitur" class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-500 hover:text-[#1B3A2D] transition-colors">Fitur Utama</a>
                    <a href="#tentang" class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-500 hover:text-[#1B3A2D] transition-colors">Tentang Sistem</a>
                </div>
                <div class="flex items-center">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold text-white transition-all bg-[#1B3A2D] border border-transparent rounded-lg shadow-sm hover:bg-[#2D5A45] hover:shadow-md focus:ring-2 focus:ring-offset-2 focus:ring-[#C8A84B]">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold text-white transition-all bg-[#1B3A2D] border border-transparent rounded-lg shadow-sm hover:bg-[#2D5A45] hover:shadow-md focus:ring-2 focus:ring-offset-2 focus:ring-[#C8A84B]">Masuk Sistem</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative pt-32 pb-20 sm:pt-40 sm:pb-24 lg:pb-32 lg:pt-48 bg-[#1B3A2D] overflow-hidden">
        <div class="absolute inset-0 bg-center bg-cover opacity-20" style="background-image: url('https://cdn.pixabay.com/photo/2021/11/02/09/27/tank-6762624_1280.jpg'); mix-blend-mode: multiply;"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
            <h1 class="text-4xl font-extrabold tracking-tight sm:text-5xl lg:text-6xl text-white">
                <span class="block">Sistem Informasi Pemeliharaan</span>
                <span class="block text-[#C8A84B] mt-2">Kendaraan Tempur</span>
            </h1>
            <p class="mt-6 max-w-2xl mx-auto text-xl text-gray-300">
                Memastikan kesiapan operasional Korps Marinir TNI AL melalui manajemen pemeliharaan terintegrasi, transparan, dan realtime.
            </p>
            <div class="mt-10 flex justify-center gap-4">
                <a href="{{ route('login') }}" class="px-8 py-3 text-base font-medium text-[#1B3A2D] bg-[#C8A84B] hover:bg-yellow-500 rounded-lg shadow-lg transition-all hover:scale-105">
                    Mulai Aplikasi
                </a>
                <a href="#fitur" class="px-8 py-3 text-base font-medium text-white bg-white/10 hover:bg-white/20 border border-white/20 rounded-lg backdrop-blur-sm transition-all">
                    Pelajari Lebih Lanjut
                </a>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div id="fitur" class="py-24 bg-white sm:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="text-base font-semibold leading-7 text-[#C8A84B] uppercase tracking-wider">Modul Terintegrasi</h2>
                <p class="mt-2 text-3xl font-bold tracking-tight text-[#1B3A2D] sm:text-4xl">Digitalisasi Pemeliharaan Ranpur</p>
            </div>
            <div class="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-24 lg:max-w-none">
                <dl class="grid max-w-xl grid-cols-1 gap-x-8 gap-y-16 lg:max-w-none lg:grid-cols-3">
                    <div class="flex flex-col bg-[#F0F2F0] rounded-2xl p-8 hover:shadow-lg transition-shadow">
                        <dt class="flex items-center gap-x-3 text-lg font-bold text-[#1B3A2D]">
                            <div class="h-12 w-12 flex items-center justify-center rounded-xl bg-[#1B3A2D] text-[#C8A84B]">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            Jadwal Presisi
                        </dt>
                        <dd class="mt-4 flex flex-auto flex-col text-base leading-7 text-gray-600">
                            <p class="flex-auto">Pemeliharaan berkalanya tercatat dan diagendakan secara digital sehingga tidak ada pemeliharaan yang terlewat.</p>
                        </dd>
                    </div>
                    <div class="flex flex-col bg-[#F0F2F0] rounded-2xl p-8 hover:shadow-lg transition-shadow">
                        <dt class="flex items-center gap-x-3 text-lg font-bold text-[#1B3A2D]">
                            <div class="h-12 w-12 flex items-center justify-center rounded-xl bg-[#1B3A2D] text-[#C8A84B]">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </div>
                            Respons Cepat
                        </dt>
                        <dd class="mt-4 flex flex-auto flex-col text-base leading-7 text-gray-600">
                            <p class="flex-auto">Pelaporan kerusakan dapat diajukan secara realtime sehingga dapat langsung ditindaklanjuti oleh Kepala Mekanik dan tim perbaikan.</p>
                        </dd>
                    </div>
                    <div class="flex flex-col bg-[#F0F2F0] rounded-2xl p-8 hover:shadow-lg transition-shadow">
                        <dt class="flex items-center gap-x-3 text-lg font-bold text-[#1B3A2D]">
                            <div class="h-12 w-12 flex items-center justify-center rounded-xl bg-[#1B3A2D] text-[#C8A84B]">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            </div>
                            Pantauan Suku Cadang
                        </dt>
                        <dd class="mt-4 flex flex-auto flex-col text-base leading-7 text-gray-600">
                            <p class="flex-auto">Integrasi manajemen persediaan suku cadang mengamankan ketersediaan material untuk operasional kendaraan tempur.</p>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-[#0B1A14]">
        <div class="max-w-7xl mx-auto px-6 py-12 md:flex md:items-center md:justify-between lg:px-8">
            <div class="flex justify-center md:order-2 space-x-6 text-gray-400">
                <span class="text-sm">&copy; {{ date('Y') }} Korps Marinir TNI AL. Hak Cipta Dilindungi.</span>
            </div>
            <div class="mt-8 flex justify-center md:mt-0 md:order-1 items-center gap-2">
                <img src="{{ asset('logo/tank.png') }}" alt="Logo" class="object-contain w-8 h-8">
                <span class="text-white font-bold text-lg">SIMRANPUR</span>
            </div>
        </div>
    </footer>
</body>
</html>
