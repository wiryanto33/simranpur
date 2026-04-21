<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Laporan Perbaikan') }}</h2>
    </x-slot>
    <div class="py-0">
        <div class="mb-4">
            <h2 class="text-xl font-bold text-gray-800">Laporan Perbaikan Kendaraan</h2>
            <p class="text-sm text-gray-500">Pantau progres dan approval perbaikan kendaraan tempur.</p>
        </div>
        @livewire('laporan-perbaikan-index')
    </div>
</x-app-layout>
