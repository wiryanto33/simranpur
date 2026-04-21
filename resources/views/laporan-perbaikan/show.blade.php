<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Laporan Perbaikan</h2></x-slot>
    <div class="py-0">
        @livewire('detail-perbaikan', ['perbaikanId' => $id])
    </div>
</x-app-layout>
