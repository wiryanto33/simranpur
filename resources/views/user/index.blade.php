<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen User') }}
        </h2>
    </x-slot>

    <div class="py-0">
        <div class="mb-4">
            <h2 class="text-xl font-bold text-gray-800">Manajemen Pengguna</h2>
            <p class="text-sm text-gray-500">Kelola data pengguna, peran, dan detail personal.</p>
        </div>

        @livewire('user-index')
    </div>
    
    @livewire('user-form')
</x-app-layout>
