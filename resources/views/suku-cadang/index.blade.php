<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Manajemen Suku Cadang') }}</h2>
    </x-slot>

    <div class="py-0">
        <div class="mb-4">
            <h2 class="text-xl font-bold text-gray-800">Inventori Suku Cadang</h2>
            <p class="text-sm text-gray-500">Kelola stok suku cadang, pantau ketersediaan, dan catat pemakaian komponen.</p>
        </div>

        @livewire('suku-cadang-index')
    </div>
</x-app-layout>
