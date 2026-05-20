<div>
    {{-- Flash Message --}}
    @if(session()->has('message'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             class="mb-4 p-4 text-sm text-green-800 bg-green-50 rounded-lg border border-green-200 flex items-center gap-2">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('message') }}
        </div>
    @endif

    {{-- Toolbar --}}
    <div class="mb-4 bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-3">
        <div class="flex flex-col md:flex-row gap-3 w-full md:w-auto">
            <div class="w-full md:w-64">
                <input wire:model.live.debounce.300ms="search" type="text"
                       placeholder="Cari nama / NRP / pangkat..."
                       class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-[#2D5A45] focus:ring focus:ring-[#2D5A45] focus:ring-opacity-50">
            </div>
            <div class="w-full md:w-40">
                <select wire:model.live="filterStatus" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-[#2D5A45] text-gray-700">
                    <option value="">Semua Status</option>
                    <option value="Aktif">Aktif</option>
                    <option value="Tidak Aktif">Tidak Aktif</option>
                </select>
            </div>
        </div>

        @can('create_mekanik')
        <button wire:click="$dispatch('createMekanik')"
                class="inline-flex items-center gap-2 px-4 py-2 bg-[#1B3A2D] text-white text-sm font-semibold rounded-md hover:bg-[#2D5A45] transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Mekanik
        </button>
        @endcan
    </div>

    {{-- Form Modal --}}
    @livewire('mekanik-form')

    {{-- Confirm Delete --}}
    @if($confirmingDeletion)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block overflow-hidden text-left align-bottom transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
                <div class="px-6 py-5">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-full bg-red-100">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Hapus Data Mekanik</h3>
                            <p class="mt-1 text-sm text-gray-500">Data mekanik ini akan dihapus permanen. Jadwal dan laporan terkait akan kehilangan referensi mekanik. Tindakan ini tidak dapat dibatalkan.</p>
                        </div>
                    </div>
                </div>
                <div class="px-6 pb-4 flex justify-end gap-3">
                    <button wire:click="$set('confirmingDeletion', false)" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Batal</button>
                    <button wire:click="delete" class="px-4 py-2 bg-red-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-red-700">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Table --}}
    <x-card>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">No.</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama & Pangkat</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">NRP</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Jabatan</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">No. HP</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($mekaniks as $m)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $mekaniks->firstItem() + $loop->index }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-[#1B3A2D] flex items-center justify-center text-white font-bold text-sm">
                                    {{ strtoupper(substr($m->nama, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-gray-900">{{ $m->nama }}</div>
                                    <div class="text-xs text-gray-500">{{ $m->pangkat ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-mono">{{ $m->nrp ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $m->jabatan ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $m->no_hp ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($m->status === 'Aktif')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                            @else
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-600">Tidak Aktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            @can('edit_mekanik')
                            <button wire:click="$dispatchTo('mekanik-form', 'editMekanik', { id: {{ $m->id }} })"
                                    class="text-blue-600 hover:text-blue-800 border border-blue-200 px-3 py-1 rounded bg-blue-50 transition-colors text-xs">
                                Edit
                            </button>
                            @endcan
                            @can('delete_mekanik')
                            <button wire:click="confirmDelete({{ $m->id }})"
                                    class="text-red-600 hover:text-red-800 border border-red-200 px-3 py-1 rounded bg-red-50 transition-colors text-xs">
                                Hapus
                            </button>
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="block text-sm font-medium">Belum ada data mekanik</span>
                            <span class="block text-xs mt-1">Tambahkan mekanik baru dengan tombol di atas.</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $mekaniks->links() }}
        </div>
    </x-card>
</div>
