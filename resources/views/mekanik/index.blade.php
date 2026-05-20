<x-app-layout>
    <x-page-header title="Data Mekanik" subtitle="Kelola data tim mekanik pemeliharaan ranpur">
        <x-slot name="actions">
            @can('create_mekanik')
            <button wire:click="$dispatch('createMekanik')"
                    class="flex items-center px-4 py-2 text-sm font-medium text-white transition-colors border border-transparent rounded-md shadow-sm bg-[#1B3A2D] hover:bg-[#2D5A45] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#C8A84B]">
                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Mekanik
            </button>
            @endcan
        </x-slot>
    </x-page-header>

    <div class="mt-8 pb-12">
        <livewire:mekanik-index />
    </div>
</x-app-layout>
