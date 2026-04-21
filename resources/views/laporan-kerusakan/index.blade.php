<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Kerusakan Kendaraan') }}
        </h2>
    </x-slot>

    <div class="py-0">
        <div class="mb-4">
            <h2 class="text-xl font-bold text-gray-800">Laporan Kerusakan</h2>
            <p class="text-sm text-gray-500">Kelola dan pantau insiden kerusakan kendaraan tempur.</p>
        </div>

        @livewire('laporan-kerusakan-index')
    </div>
</x-app-layout>
