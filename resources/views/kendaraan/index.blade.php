<x-app-layout>
    <x-page-header title="Data Kendaraan Tempur" subtitle="Manajemen master data kendaraan tempur dan status kesiapan operasional">
        <x-slot name="actions">
            @role('Admin|KepMek')
            <!-- Livewire button trigger handled inside the component or simple href if separated -->
            @endrole
        </x-slot>
    </x-page-header>

    @livewire('kendaraan-index')
</x-app-layout>
