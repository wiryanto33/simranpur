<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Role & Permission') }}
        </h2>
    </x-slot>

    <div class="py-0">
        <div class="mb-4">
            <h2 class="text-xl font-bold text-gray-800">Role & Permission</h2>
            <p class="text-sm text-gray-500">Buat atau kelola Role Akses sistem beserta hak izin (permissions) yang dimiliki.</p>
        </div>

        @livewire('role-index')
    </div>
    
    @livewire('role-form')
</x-app-layout>
