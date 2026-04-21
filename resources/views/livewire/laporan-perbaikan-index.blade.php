<div>
    @if (session()->has('message'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 border border-green-200">
            <span class="font-medium">Berhasil:</span> {{ session('message') }}
        </div>
    @endif

    <div class="mb-4 bg-white p-4 rounded-lg shadow-sm border border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
        <div class="flex gap-3 w-full md:w-auto">
            <select wire:model.live="filterStatus" class="text-sm rounded-md border-gray-300 shadow-sm focus:border-[#2D5A45] text-gray-700">
                <option value="">Semua Status</option>
                <option value="Menunggu Approval">Menunggu Approval</option>
                <option value="Disetujui">Disetujui</option>
                <option value="Perlu Revisi">Perlu Revisi</option>
            </select>
        </div>
    </div>

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
                            <h3 class="text-lg font-medium text-gray-900">Hapus Laporan Perbaikan</h3>
                            <p class="mt-1 text-sm text-gray-500">Laporan perbaikan ini akan dihapus permanen. Stok suku cadang yang digunakan akan dikembalikan, dan status laporan kerusakan terkait akan di-rollback. Tindakan ini tidak dapat dibatalkan.</p>
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
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. Perbaikan</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kendaraan Tempur</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mekanik</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Periode Perbaikan</th>
                        <th class="px-5 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-5 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($perbaikans as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="font-mono text-sm font-bold text-gray-700">LP-{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}</span>
                                <div class="text-xs text-gray-400">dari LK-{{ str_pad($item->laporan_kerusakan_id, 4, '0', STR_PAD_LEFT) }}</div>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-900">{{ $item->laporanKerusakan->kendaraan->nama ?? '-' }}</div>
                                <div class="text-xs text-gray-500">{{ $item->laporanKerusakan->kendaraan->no_register ?? '-' }}</div>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $item->mekanik->name ?? '-' }}</div>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-sm text-gray-700">
                                <div>{{ $item->tanggal_mulai?->format('d M Y') }}</div>
                                @if($item->tanggal_selesai)
                                    <div class="text-gray-400">s/d {{ $item->tanggal_selesai->format('d M Y') }}</div>
                                @else
                                    <div class="text-orange-500 font-medium text-xs">Belum selesai</div>
                                @endif
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-center">
                                @php
                                    $stColor = match($item->status) {
                                        'Menunggu Approval' => 'bg-yellow-100 text-yellow-800',
                                        'Disetujui'        => 'bg-green-100 text-green-800',
                                        'Perlu Revisi'     => 'bg-red-100 text-red-800',
                                        default            => 'bg-gray-100 text-gray-800',
                                    };
                                @endphp
                                <span class="px-2 py-1 inline-flex text-xs font-bold rounded-full {{ $stColor }}">{{ $item->status }}</span>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                @can('view_laporan_perbaikan')
                                <a href="{{ route('laporan-perbaikan.show', $item->id) }}" class="text-indigo-600 hover:text-indigo-900 border border-indigo-200 px-3 py-1 rounded bg-indigo-50">Detail</a>
                                @endcan

                                @can('edit_laporan_perbaikan')
                                @if($item->status === 'Menunggu Approval' && auth()->user()->hasAnyRole(['KepMek', 'Admin']))
                                    <button wire:click="approve({{ $item->id }})" wire:confirm="Setujui laporan perbaikan ini?" class="text-green-700 hover:text-green-900 border border-green-300 px-3 py-1 rounded bg-green-50">Setujui</button>
                                    <button wire:click="kembalikan({{ $item->id }})" class="text-yellow-600 hover:text-yellow-900 border border-yellow-300 px-3 py-1 rounded bg-yellow-50">Revisi</button>
                                @endif
                                
                                @if($item->status !== 'Disetujui' && (auth()->id() === $item->mekanik_id || auth()->user()->hasAnyRole(['KepMek', 'Admin'])))
                                    <button wire:click="$dispatchTo('laporan-perbaikan-form', 'editPerbaikan', { id: {{ $item->id }} })" class="text-blue-600 hover:text-blue-900 border border-blue-200 px-3 py-1 rounded bg-blue-50">Edit</button>
                                @endif
                                @endcan

                                @can('delete_laporan_perbaikan')
                                    <button wire:click="confirmDelete({{ $item->id }})" class="text-red-600 hover:text-red-900 border border-red-200 px-3 py-1 rounded bg-red-50">Hapus</button>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    </svg>
                                    <span class="text-sm font-medium">Belum ada Laporan Perbaikan.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $perbaikans->links() }}</div>
    </x-card>

    @livewire('laporan-perbaikan-form')
</div>
