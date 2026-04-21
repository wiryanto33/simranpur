<div>
    @if (session()->has('message'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 border border-green-200">
            {{ session('message') }}
        </div>
    @endif

    <!-- Stats & Toolbar -->
    <div class="mb-4 bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="flex flex-col md:flex-row gap-3 w-full md:w-auto">
            <div class="relative w-full md:w-64">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari kode atau nama..." class="w-full pl-10 text-sm rounded-md border-gray-300 shadow-sm focus:border-[#2D5A45] focus:ring focus:ring-[#2D5A45] focus:ring-opacity-50">
                <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            @if($totalStokMenipis > 0)
            <button wire:click="$set('filterStok', filterStok === 'menipis' ? '' : 'menipis')"
                class="inline-flex items-center gap-2 px-3 py-2 text-xs font-semibold rounded-md border transition-colors {{ $filterStok === 'menipis' ? 'bg-red-600 text-white border-red-600' : 'bg-red-50 text-red-700 border-red-300 hover:bg-red-100' }}">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                Stok Menipis ({{ $totalStokMenipis }})
            </button>
            @endif
        </div>

        @can('create_suku_cadang')
        <button wire:click="$dispatchTo('suku-cadang-form', 'buatSukuCadang')"
            class="inline-flex items-center gap-2 px-4 py-2 bg-[#C8A84B] text-[#1B3A2D] font-semibold rounded-md shadow-sm hover:bg-yellow-400 transition-colors text-sm shrink-0">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Suku Cadang
        </button>
        @endcan
    </div>

    <!-- Confirm Delete -->
    @if($confirmingDeletion)
    <div class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center bg-gray-900 bg-opacity-50">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6 mx-4">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-900">Hapus Suku Cadang?</h3>
                    <p class="text-sm text-gray-500 mt-1">Tindakan ini tidak dapat dibatalkan. Data suku cadang dan foto terkait akan dihapus permanen.</p>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button wire:click="$set('confirmingDeletion', false)" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">Batal</button>
                <button wire:click="delete" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">Hapus Permanen</button>
            </div>
        </div>
    </div>
    @endif

    <!-- Card Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
        @forelse($items as $item)
            @php
                $isLow = $item->stok <= $item->stok_minimum;
                $stokPct = $item->stok_minimum > 0 ? min(100, ($item->stok / max($item->stok_minimum * 2, 1)) * 100) : 100;
            @endphp
            <div class="bg-white rounded-xl border {{ $isLow ? 'border-red-300 shadow-red-50' : 'border-gray-200' }} shadow-sm hover:shadow-md transition-shadow flex flex-col overflow-hidden group">

                <!-- Foto Area -->
                <div class="relative w-full h-40 bg-gray-100 overflow-hidden">
                    @if($item->foto)
                        <img src="{{ Storage::url($item->foto) }}" alt="{{ $item->nama }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    @else
                        <div class="flex flex-col items-center justify-center w-full h-full text-gray-300 bg-gradient-to-br from-gray-100 to-gray-200">
                            <svg class="w-14 h-14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="text-xs mt-1">Tidak ada foto</span>
                        </div>
                    @endif

                    <!-- Kode Badge -->
                    <div class="absolute top-2 left-2">
                        <span class="px-2 py-1 text-[10px] font-mono font-bold bg-[#1B3A2D] text-[#C8A84B] rounded-md shadow">{{ $item->kode }}</span>
                    </div>

                    <!-- Low Stock Warning -->
                    @if($isLow)
                    <div class="absolute top-2 right-2">
                        <span class="px-2 py-1 text-[10px] font-bold bg-red-600 text-white rounded-md shadow flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            Menipis!
                        </span>
                    </div>
                    @endif
                </div>

                <!-- Card Body -->
                <div class="p-4 flex-1 flex flex-col">
                    <h3 class="font-bold text-gray-900 text-sm leading-snug mb-1 line-clamp-2">{{ $item->nama }}</h3>
                    @if($item->lokasi)
                        <p class="text-xs text-gray-400 mb-3 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $item->lokasi }}
                        </p>
                    @endif

                    <!-- Stok Info -->
                    <div class="mt-auto">
                        <div class="flex justify-between text-xs text-gray-500 mb-1">
                            <span>Stok</span>
                            <span class="font-bold {{ $isLow ? 'text-red-600' : 'text-gray-800' }}">{{ $item->stok }} {{ $item->satuan }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-1.5 mb-1">
                            <div class="h-1.5 rounded-full {{ $isLow ? 'bg-red-500' : 'bg-[#2D5A45]' }}" style="width: {{ $stokPct }}%"></div>
                        </div>
                        <p class="text-[10px] text-gray-400">Min. stok: {{ $item->stok_minimum }} {{ $item->satuan }}</p>
                    </div>
                </div>

                <!-- Card Footer Actions -->
                @canany(['edit_suku_cadang','delete_suku_cadang'])
                <div class="border-t border-gray-100 px-4 py-3 flex justify-end gap-2 bg-gray-50">
                    @can('edit_suku_cadang')
                    <button wire:click="$dispatchTo('suku-cadang-form', 'editSukuCadang', { id: {{ $item->id }} })"
                        class="text-xs font-medium text-blue-600 hover:text-blue-800 border border-blue-200 px-3 py-1 rounded bg-blue-50 hover:bg-blue-100 transition-colors">
                        Edit
                    </button>
                    @endcan
                    @can('delete_suku_cadang')
                    <button wire:click="confirmDelete({{ $item->id }})"
                        class="text-xs font-medium text-red-600 hover:text-red-800 border border-red-200 px-3 py-1 rounded bg-red-50 hover:bg-red-100 transition-colors">
                        Hapus
                    </button>
                    @endcan
                </div>
                @endcanany
            </div>
        @empty
            <div class="col-span-full text-center py-16">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <p class="text-gray-500 font-medium">Belum ada data suku cadang</p>
                <p class="text-gray-400 text-sm mt-1">Klik "Tambah Suku Cadang" untuk memulai.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $items->links() }}
    </div>

    <!-- Include Form Modal -->
    @livewire('suku-cadang-form')
</div>
