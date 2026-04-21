<div>
    @if (session()->has('message'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
            <span class="font-medium">Pemberitahuan:</span> {{ session('message') }}
        </div>
    @endif

    <div class="mb-4 bg-white p-4 rounded-lg shadow-sm border border-gray-100 flex flex-col space-y-3">
        <div class="flex flex-col md:flex-row gap-3">
            <div class="w-full md:w-1/5">
                <label class="block text-xs text-gray-500 mb-1">Bulan/Tahun</label>
                <input wire:model.live="filterBulan" type="month" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#2D5A45] focus:ring focus:ring-[#2D5A45] focus:ring-opacity-50 text-sm">
            </div>
            <div class="w-full md:w-1/5">
                <label class="block text-xs text-gray-500 mb-1">Status</label>
                <select wire:model.live="filterStatus" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#2D5A45] focus:ring focus:ring-[#2D5A45] focus:ring-opacity-50 text-sm">
                    <option value="">Semua Status</option>
                    <option value="Terjadwal">Terjadwal</option>
                    <option value="Sedang Dikerjakan">Sedang Dikerjakan</option>
                    <option value="Selesai">Selesai</option>
                    <option value="Ditunda">Ditunda</option>
                    <option value="Dibatalkan">Dibatalkan</option>
                </select>
            </div>
            <div class="w-full md:w-1/5">
                <label class="block text-xs text-gray-500 mb-1">Jenis</label>
                <select wire:model.live="filterJenis" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#2D5A45] focus:ring focus:ring-[#2D5A45] focus:ring-opacity-50 text-sm">
                    <option value="">Semua Jenis</option>
                    <option value="Harian">Harian</option>
                    <option value="Mingguan">Mingguan</option>
                    <option value="Bulanan">Bulanan</option>
                    <option value="Triwulan">Triwulan</option>
                    <option value="Tahunan">Tahunan</option>
                    <option value="Insidentil">Insidentil</option>
                </select>
            </div>
            <div class="w-full md:w-1/5">
                <label class="block text-xs text-gray-500 mb-1">Mekanik</label>
                <select wire:model.live="filterMekanik" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#2D5A45] focus:ring focus:ring-[#2D5A45] focus:ring-opacity-50 text-sm">
                    <option value="">Semua Mekanik</option>
                    @foreach($mekaniks as $m)
                        <option value="{{ $m->id }}">{{ $m->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full md:w-1/5">
                <label class="block text-xs text-gray-500 mb-1">Kendaraan</label>
                <select wire:model.live="filterKendaraan" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#2D5A45] focus:ring focus:ring-[#2D5A45] focus:ring-opacity-50 text-sm">
                    <option value="">Semua Ranpur</option>
                    @foreach($kendaraans as $k)
                        <option value="{{ $k->id }}">{{ $k->nomor_ranpur }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <div class="flex justify-between items-center mt-2 pt-2 border-t border-gray-100">
            <div>
                <button wire:click="exportCsv" class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded text-xs font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="mr-1.5 h-4 w-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                    </svg>
                    Export CSV / Excel
                </button>
            </div>
            <div>
                @can('create_jadwal_pemeliharaan')
                <button wire:click="$dispatchTo('jadwal-form', 'createJadwal')" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#1B3A2D] hover:bg-[#2D5A45]">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Jadwal
                </button>
                @endcan
            </div>
        </div>
    </div>

    @if($confirmingDeletion)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-red-100 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">Hapus Jadwal</h3>
                            <div class="mt-2 text-sm text-gray-500">Apakah Anda yakin ingin membatalkan dan menghapus jadwal ini?</div>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="deleteJadwal" type="button" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 sm:ml-3 sm:w-auto sm:text-sm">Hapus</button>
                    <button wire:click="$set('confirmingDeletion', false)" type="button" class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kendaraan (No Ranpur)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Pemeliharaan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mekanik</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($jadwalList as $j)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                {{ $j->tanggal->format('d M Y') }}
                                @if($j->estimasi_hari > 1)
                                    <span class="block text-xs text-gray-500">{{ $j->estimasi_hari }} Hari</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $j->kendaraan->nama ?? '-' }}
                                <span class="block text-xs text-gray-500">{{ $j->kendaraan->nomor_ranpur ?? '' }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $j->jenis_pemeliharaan }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @foreach($j->mekanik as $m)
                                    <span class="block">• {{ $m->name }}</span>
                                @endforeach
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @php
                                    $color = match($j->status) {
                                        'Terjadwal' => 'bg-gray-100 text-gray-800',
                                        'Sedang Dikerjakan' => 'bg-yellow-100 text-yellow-800',
                                        'Selesai' => 'bg-green-100 text-green-800',
                                        'Ditunda' => 'bg-orange-100 text-orange-800',
                                        'Dibatalkan' => 'bg-red-100 text-red-800',
                                        default => 'bg-gray-100 text-gray-800'
                                    };
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $color }}">
                                    {{ $j->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button wire:click="viewDetails({{ $j->id }})" class="text-blue-600 hover:text-blue-900 mr-2">Detail</button>
                                
                                @php
                                    $isAssignedMekanik = $j->mekanik->contains(auth()->id());
                                @endphp

                                @if(auth()->user()->can('edit_jadwal_pemeliharaan') || (auth()->user()->hasRole('Mekanik') && $isAssignedMekanik))
                                    <select 
                                        x-data 
                                        @change="$wire.updateStatus({{ $j->id }}, $event.target.value); $event.target.value=''" 
                                        class="text-xs border-gray-300 rounded text-gray-600 py-1 pl-2 pr-6 bg-white hover:bg-gray-50 shadow-sm transition-colors cursor-pointer mr-2 focus:ring-[#2D5A45] focus:border-[#2D5A45]"
                                    >
                                        <option value="" disabled selected>Ubah Status</option>
                                        @can('edit_jadwal_pemeliharaan')
                                            <option value="Terjadwal">Set Terjadwal</option>
                                        @endcan
                                        
                                        @if($j->status != 'Selesai')
                                            <option value="Sedang Dikerjakan" class="text-yellow-700">Sedang Dikerjakan</option>
                                            <option value="Selesai" class="text-green-700">Tandai Selesai</option>
                                        @endif
                                        
                                        @can('edit_jadwal_pemeliharaan')
                                            <option value="Ditunda" class="text-orange-700">Ditunda</option>
                                            <option value="Dibatalkan" class="text-red-700">Batalkan / Hapus</option>
                                        @endcan
                                    </select>
                                @endif

                                @can('edit_jadwal_pemeliharaan')
                                <button wire:click="$dispatchTo('jadwal-form', 'editJadwal', { id: {{ $j->id }} })" class="text-indigo-600 hover:text-indigo-900 ml-3">Edit</button>
                                @endcan
                                @can('delete_jadwal_pemeliharaan')
                                <button wire:click="confirmDelete({{ $j->id }})" class="text-red-600 hover:text-red-900 ml-3">Hapus</button>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                Data jadwal pemeliharaan tidak ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $jadwalList->links() }}
        </div>
    </x-card>

    <!-- Detail Modal -->
    @if($showDetail && $selectedJadwal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block overflow-hidden text-left align-middle transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:max-w-2xl sm:w-full">
                <div class="px-6 py-4 bg-[#1B3A2D] flex justify-between items-center text-white">
                    <h3 class="text-lg font-bold">DETAIL JADWAL PEMELIHARAAN</h3>
                    <button wire:click="$set('showDetail', false)" class="text-gray-300 hover:text-white focus:outline-none">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="px-6 py-6 bg-white space-y-6">
                    <!-- Data Ranpur -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Kendaraan / Ranpur</label>
                                <div class="text-base font-bold text-gray-800">{{ $selectedJadwal->kendaraan->nama ?? '-' }}</div>
                                <div class="text-sm text-gray-600">{{ $selectedJadwal->kendaraan->nomor_ranpur ?? '' }}</div>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Jenis Pemeliharaan</label>
                                <div class="mt-1">
                                    <span class="px-2 py-1 text-xs font-bold rounded bg-blue-100 text-blue-800">
                                        {{ $selectedJadwal->jenis_pemeliharaan }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Waktu Pelaksanaan</label>
                                <div class="text-base font-bold text-gray-800">{{ $selectedJadwal->tanggal->format('d F Y') }}</div>
                                <div class="text-sm text-gray-600">Estimasi: {{ $selectedJadwal->estimasi_hari }} Hari</div>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Status Saat Ini</label>
                                <div class="mt-1">
                                     @php
                                        $statusColor = match($selectedJadwal->status) {
                                            'Terjadwal' => 'bg-gray-100 text-gray-800',
                                            'Sedang Dikerjakan' => 'bg-yellow-100 text-yellow-800',
                                            'Selesai' => 'bg-green-100 text-green-800',
                                            'Ditunda' => 'bg-orange-100 text-orange-800',
                                            'Dibatalkan' => 'bg-red-100 text-red-800',
                                            default => 'bg-gray-100 text-gray-800'
                                        };
                                    @endphp
                                    <span class="px-2 py-1 text-xs font-bold rounded {{ $statusColor }}">
                                        {{ $selectedJadwal->status }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-4">
                        <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Tim Mekanik Terpilih</label>
                        <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-2">
                            @foreach($selectedJadwal->mekanik as $m)
                                <div class="flex items-center p-2 bg-gray-50 border border-gray-100 rounded-lg">
                                    <div class="w-8 h-8 rounded-full bg-[#1B3A2D] flex items-center justify-center text-white text-xs font-bold mr-3 shadow-sm">
                                        {{ substr($m->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-gray-800">{{ $m->name }}</div>
                                        <div class="text-[10px] text-gray-500 uppercase">{{ $m->detail->pangkat ?? 'Mekanik' }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    @if($selectedJadwal->keterangan)
                    <div class="border-t border-gray-100 pt-4">
                        <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Catatan / Keterangan</label>
                        <div class="mt-1 p-3 bg-gray-50 border-l-4 border-gray-300 text-sm text-gray-700 italic">
                            {{ $selectedJadwal->keterangan }}
                        </div>
                    </div>
                    @endif

                    <div class="border-t border-gray-100 pt-4">
                        <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 block">Daftar Checklist Pekerjaan</label>
                        <div class="space-y-1 bg-gray-50 p-4 rounded-lg border border-gray-100">
                            @foreach($selectedJadwal->checklist ?? [] as $item)
                            @php
                                $isDone = is_array($item) ? ($item['is_done'] ?? false) : false;
                                $taskName = is_array($item) ? ($item['task'] ?? '') : $item;
                            @endphp
                            <div class="flex items-center space-x-3 text-sm py-1">
                                <div @class([
                                    'w-4 h-4 rounded-full border flex items-center justify-center',
                                    'bg-green-500 border-green-500' => $isDone,
                                    'border-gray-300' => !$isDone
                                ])>
                                    @if($isDone)
                                        <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                        </svg>
                                    @endif
                                </div>
                                <span class="{{ $isDone ? 'line-through text-gray-400 font-medium' : 'text-gray-700 font-medium' }}">
                                    {{ $taskName }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 flex flex-col md:flex-row justify-between gap-3 border-t">
                    <a href="{{ route('jadwal.cetak-pdf', $selectedJadwal->id) }}" target="_blank" class="inline-flex items-center justify-center px-6 py-2.5 bg-red-700 text-white rounded-md text-sm font-bold hover:bg-red-800 transition-colors shadow-md">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        CETAK SURAT JADWAL
                    </a>
                    <button wire:click="$set('showDetail', false)" class="px-6 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-md text-sm font-bold hover:bg-gray-100 transition-colors shadow-sm">
                        TUTUP
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
