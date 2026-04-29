<div>
    @if (session()->has('message'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50">
            <span class="font-medium">Notifikasi:</span> {{ session('message') }}
        </div>
    @endif
    
    <div class="mb-4 bg-white p-4 rounded-lg shadow-sm border border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        
        <div class="flex flex-col md:flex-row gap-3 w-full md:w-auto">
            <div class="w-full md:w-64">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari Ranpur..." class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-[#2D5A45] focus:ring focus:ring-[#2D5A45] focus:ring-opacity-50">
            </div>
            <div class="w-full md:w-40">
                <select wire:model.live="filterStatus" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-[#2D5A45] text-gray-700">
                    <option value="">Semua Status</option>
                    <option value="Menunggu">Menunggu</option>
                    <option value="Diverifikasi">Diverifikasi</option>
                    <option value="Menunggu Suku Cadang">Menunggu Suku Cadang</option>
                    <option value="Siap Diperbaiki">Siap Diperbaiki</option>
                    <option value="Selesai">Selesai</option>
                    <option value="Ditolak">Ditolak</option>
                </select>
            </div>
            <div class="w-full md:w-40">
                <select wire:model.live="filterPrioritas" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-[#2D5A45] text-gray-700">
                    <option value="">Semua Prioritas</option>
                    <option value="Rendah">🔴 Rendah</option>
                    <option value="Sedang">🟡 Sedang</option>
                    <option value="Tinggi">🟢 Tinggi</option>
                </select>
            </div>
        </div>

        <div>
            @can('create_laporan_kerusakan')
        <button wire:click="$dispatchTo('laporan-kerusakan-form', 'buatLaporan')" class="inline-flex items-center px-4 py-2 bg-[#C8A84B] border border-transparent rounded-md font-semibold text-[#1B3A2D] hover:bg-yellow-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#C8A84B] transition-colors text-sm shadow-sm gap-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Lapor Kerusakan Baru
        </button>
        @endcan
        </div>
    </div>

    @livewire('laporan-kerusakan-form')
    @livewire('permintaan-suku-cadang-form')
    @livewire('laporan-perbaikan-form')

    {{-- Confirm Delete Modal --}}
    @if($confirmingDeletion)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block overflow-hidden text-left align-bottom transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="px-6 py-5">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-full bg-red-100">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Hapus Laporan Kerusakan</h3>
                            <p class="mt-1 text-sm text-gray-500">Laporan ini akan dihapus permanen. Jika status kendaraan masih 'Menunggu', status kendaraan akan dikembalikan ke 'Siap Tempur'. Tindakan ini tidak dapat dibatalkan.</p>
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

    <x-card>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Info Ranpur</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal & Pelapor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kerusakan</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Prioritas</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($laporans as $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @php
                                        // Menampilkan foto pertama jika ada
                                        $fotoUrl = asset('images/default-tank.png');
                                        if($item->foto && is_array($item->foto) && count($item->foto) > 0) {
                                            $fotoUrl = Storage::url($item->foto[0]);
                                        }
                                    @endphp
                                    <div class="flex-shrink-0 h-10 w-10 relative group">
                                        <div class="h-10 w-10 rounded shadow-sm bg-gray-200 bg-cover bg-center" style="background-image: url('{{ $fotoUrl }}')"></div>
                                        @if($item->foto && is_array($item->foto) && count($item->foto) > 1)
                                            <div class="absolute -top-2 -right-2 bg-blue-500 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full border border-white">
                                                +{{ count($item->foto) - 1 }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-bold text-gray-900">{{ $item->kendaraan->nama ?? '-' }}</div>
                                        <div class="text-xs text-gray-500">{{ $item->kendaraan->nomor_ranpur ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $item->tanggal->format('d M Y') }}</div>
                                <div class="text-xs text-gray-500">Oleh: {{ $item->pelapor->name ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 line-clamp-2" title="{{ $item->deskripsi }}">{{ $item->deskripsi }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @php
                                    $prioColor = match($item->tingkat_prioritas) {
                                        'Tinggi' => 'bg-red-100 text-red-800 border-red-200',
                                        'Sedang' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                        'Rendah' => 'bg-green-100 text-green-800 border-green-200',
                                        default => 'bg-gray-100 text-gray-800 border-gray-200'
                                    };
                                @endphp
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded border {{ $prioColor }}">
                                    {{ $item->tingkat_prioritas }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @php
                                    $stColor = match($item->status) {
                                        'Menunggu' => 'bg-orange-100 text-orange-800',
                                        'Diverifikasi' => 'bg-blue-100 text-blue-800',
                                        'Menunggu Suku Cadang' => 'bg-purple-100 text-purple-800',
                                        'Siap Diperbaiki' => 'bg-cyan-100 text-cyan-800',
                                        'Selesai' => 'bg-green-100 text-green-800',
                                        'Ditolak' => 'bg-gray-100 text-gray-800',
                                        default => 'bg-gray-100 text-gray-800'
                                    };
                                @endphp
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-bold rounded-full {{ $stColor }}">
                                    {{ $item->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                @can('view_laporan_kerusakan')
                                <a href="{{ route('laporan-kerusakan.show', $item->id) }}" class="text-indigo-600 hover:text-indigo-900 border border-indigo-200 px-3 py-1 rounded bg-indigo-50 transition-colors">Lihat Detail</a>
                                @endcan

                                @can('create_permintaan_suku_cadang')
                                @if($item->status === 'Diverifikasi')
                                <button wire:click="$dispatchTo('permintaan-suku-cadang-form', 'buatPermintaan', { laporanId: {{ $item->id }} })" class="text-purple-600 hover:text-purple-800 border border-purple-200 px-3 py-1 rounded bg-purple-50 transition-colors">Minta Sparepart</button>
                                @endif
                                @endcan

                                @can('create_laporan_perbaikan')
                                @if($item->status === 'Siap Diperbaiki')
                                <button wire:click="$dispatchTo('laporan-perbaikan-form', 'buatPerbaikan', { id_kerusakan: {{ $item->id }} })" class="text-green-600 hover:text-green-800 border border-green-200 px-3 py-1 rounded bg-green-50 transition-colors">Buat Perbaikan</button>
                                @endif
                                @endcan

                                @can('edit_laporan_kerusakan')
                                @if(in_array($item->status, ['Menunggu', 'Diverifikasi']))
                                <button wire:click="$dispatchTo('laporan-kerusakan-form', 'editLaporan', { id: {{ $item->id }} })" class="text-blue-600 hover:text-blue-800 border border-blue-200 px-3 py-1 rounded bg-blue-50 transition-colors">Edit</button>
                                @endif
                                @endcan
                                @can('delete_laporan_kerusakan')
                                <button wire:click="confirmDelete({{ $item->id }})" class="text-red-600 hover:text-red-800 border border-red-200 px-3 py-1 rounded bg-red-50 transition-colors">Hapus</button>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span class="block text-sm font-medium">Belum ada Data Laporan Kerusakan</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $laporans->links() }}
        </div>
    </x-card>
</div>
