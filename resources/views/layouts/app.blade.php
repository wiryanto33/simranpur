<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="bg-[#F0F2F0]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SIMRANPUR') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-800 bg-[#F0F2F0]" x-data="document.addEventListener('alpine:init', () => {}) || {
        sidebarOpen: false,
        sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',
        toggleSidebar() {
            if (window.innerWidth >= 1024) {
                this.sidebarCollapsed = !this.sidebarCollapsed;
                localStorage.setItem('sidebarCollapsed', this.sidebarCollapsed);
            } else {
                this.sidebarOpen = !this.sidebarOpen;
            }
        }
    }" 
    x-init="$watch('sidebarCollapsed', val => localStorage.setItem('sidebarCollapsed', val))"
>
    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
        @include('layouts.partials.sidebar')

        <!-- Main Content Wrapper -->
        <div class="flex flex-col flex-1 w-full overflow-hidden transition-all duration-300">
            <!-- Topbar -->
            @include('layouts.partials.topbar')

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto overflow-x-hidden bg-[#F0F2F0] p-4 md:p-6 lg:p-8">
                <!-- Page Content -->
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
