<div class="space-y-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <h2 class="text-xl font-bold text-gray-800">Daftar Permintaan Suku Cadang</h2>
        
        <div class="flex gap-2">
            <select wire:model.live="filterStatus" class="text-sm rounded-md border-gray-300 shadow-sm focus:border-[#1B3A2D] focus:ring-[#1B3A2D]">
                <option value="">Semua Status</option>
                <option value="Pending">🕒 Pending</option>
                <option value="Approved">✅ Disetujui</option>
                <option value="Rejected">❌ Ditolak</option>
            </select>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="p-4 text-sm text-green-800 rounded-lg bg-green-50 border border-green-200">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="p-4 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200">
            {{ session('error') }}
        </div>
    @endif

    <x-card>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit & Mekanik</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tgl Permintaan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Suku Cadang</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($requests as $req)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900">{{ $req->laporanKerusakan->kendaraan->nama ?? '-' }}</div>
                                <div class="text-xs text-gray-500">Mekanik: {{ $req->mekanik->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $req->tanggal_permintaan->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-xs text-gray-600">
                                    @foreach($req->details as $d)
                                        <div class="truncate max-w-[200px]">• {{ $d->sukuCadang->nama }} ({{ $d->jumlah }} {{ $d->sukuCadang->satuan }})</div>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @php
                                    $stColor = match($req->status) {
                                        'Pending' => 'bg-orange-100 text-orange-800',
                                        'Approved' => 'bg-green-100 text-green-800',
                                        'Rejected' => 'bg-red-100 text-red-800',
                                        default => 'bg-gray-100 text-gray-800'
                                    };
                                @endphp
                                <span class="px-2 py-1 inline-flex text-[10px] font-bold rounded-full {{ $stColor }}">{{ strtoupper($req->status) }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-1">
                                @if($req->status === 'Pending' && auth()->user()->can('approve_permintaan_suku_cadang'))
                                    <button wire:click="showApprove({{ $req->id }})" class="text-green-600 hover:text-green-800 border border-green-200 px-3 py-1 rounded bg-green-50 transition-colors">Setujui</button>
                                    <button wire:click="showReject({{ $req->id }})" class="text-red-600 hover:text-red-800 border border-red-200 px-3 py-1 rounded bg-red-50 transition-colors">Tolak</button>
                                @endif
                                <button wire:click="viewDetails({{ $req->id }})" class="text-blue-600 hover:text-blue-800 px-2 py-1">Detail</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500 italic">Belum ada permintaan suku cadang.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $requests->links() }}</div>
    </x-card>

    {{-- Detail Modal --}}
    @if($activePermintaanId && $activePermintaan)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900 bg-opacity-75">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-lg">
            <div class="px-6 py-4 border-b flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800">Detail Permintaan #{{ $activePermintaanId }}</h3>
                <button wire:click="$set('activePermintaanId', null)" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-6 space-y-4 text-sm">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-500">Mekanik</p>
                        <p class="font-semibold">{{ $activePermintaan->mekanik->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Unit Ranpur</p>
                        <p class="font-semibold">{{ $activePermintaan->laporanKerusakan->kendaraan->nama }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Status</p>
                        <p class="font-bold">{{ $activePermintaan->status }}</p>
                    </div>
                    @if($activePermintaan->checker)
                    <div>
                        <p class="text-gray-500">Diproses Oleh</p>
                        <p class="font-semibold">{{ $activePermintaan->checker->name }}</p>
                    </div>
                    @endif
                </div>

                <div class="bg-gray-50 p-4 rounded border">
                    <p class="font-bold text-gray-700 mb-2">Item Suku Cadang:</p>
                    <ul class="space-y-1">
                        @foreach($activePermintaan->details as $d)
                        <li class="flex justify-between">
                            <span>{{ $d->sukuCadang->nama }}</span>
                            <span class="font-mono">{{ $d->jumlah }} {{ $d->sukuCadang->satuan }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>

                @if($activePermintaan->status === 'Rejected')
                <div class="bg-red-50 p-3 rounded border border-red-100">
                    <p class="text-red-700 font-bold">Alasan Penolakan:</p>
                    <p class="text-red-600 italic small">{{ $activePermintaan->alasan_penolakan ?: 'Tidak disertakan' }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    {{-- Approval Confirmation --}}
    @if($confirmingApproval)
    <div class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-gray-900 bg-opacity-75">
        <div class="bg-white rounded-lg shadow-2xl p-6 max-w-sm text-center">
            <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Setujui Permintaan?</h3>
            <p class="text-sm text-gray-600 mb-6">Stok suku cadang akan langsung dikurangi dan tercatat sebagai penggunaan perbaikan.</p>
            <div class="flex gap-3">
                <button wire:click="$set('confirmingApproval', false)" class="flex-1 px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">Batal</button>
                <button wire:click="approve" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-md text-sm font-medium hover:bg-green-700">Ya, Setujui</button>
            </div>
        </div>
    </div>
    @endif

    {{-- Rejection Form --}}
    @if($confirmingRejection)
    <div class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-gray-900 bg-opacity-75">
        <div class="bg-white rounded-lg shadow-2xl p-6 max-w-md w-full">
            <h3 class="text-lg font-bold text-gray-900 mb-4 text-center">Tolak Permintaan</h3>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Alasan Penolakan</label>
                <textarea wire:model="alasanPenolakan" rows="3" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" placeholder="Contoh: Stok sebenarnya fisik kosong, gunakan item alternatif..."></textarea>
            </div>
            <div class="flex gap-3">
                <button wire:click="$set('confirmingRejection', false)" class="flex-1 px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">Batal</button>
                <button wire:click="reject" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-md text-sm font-medium hover:bg-red-700">Tolak Permintaan</button>
            </div>
        </div>
    </div>
    @endif
</div>
