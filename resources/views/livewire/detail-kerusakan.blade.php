<div>
    @if (session()->has('success'))
        <div class="mb-4 bg-green-50 p-4 rounded-md border border-green-200">
            <p class="text-sm text-green-800"><span class="font-bold">Berhasil:</span> {{ session('success') }}</p>
        </div>
    @endif

    @if (session()->has('warning'))
        <div class="mb-4 bg-orange-50 p-4 rounded-md border border-orange-200">
            <p class="text-sm text-orange-800"><span class="font-bold">Info:</span> {{ session('warning') }}</p>
        </div>
    @endif

    <div class="mb-4">
        <a href="{{ route('laporan-kerusakan.index') }}" class="inline-flex items-center text-sm text-[#2D5A45] hover:underline font-medium">
            <svg class="mr-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar
        </a>
    </div>

    <!-- Header & Info -->
    <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden mb-6">
        <div class="bg-gray-50 border-b border-gray-200 px-6 py-4 flex flex-col md:flex-row justify-between items-start md:items-center p-4">
            <div>
                <h3 class="text-lg font-bold text-[#1B3A2D] uppercase flex items-center gap-2">
                    Laporan Kerusakan #LK-{{ str_pad($laporan->id, 4, '0', STR_PAD_LEFT) }}
                </h3>
                <p class="text-sm text-gray-500 mt-1">Dilaporkan pada {{ $laporan->tanggal->format('d M Y H:i') }} oleh <b>{{ $laporan->pelapor->name ?? '-' }}</b></p>
            </div>
            <div class="mt-4 md:mt-0 flex gap-2">
                @php
                    $stColor = match($laporan->status) {
                        'Menunggu' => 'bg-orange-100 text-orange-800',
                        'Diverifikasi' => 'bg-blue-100 text-blue-800 border-blue-200',
                        'Dalam Proses' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                        'Selesai' => 'bg-green-100 text-green-800 border-green-200',
                        'Ditolak' => 'bg-gray-100 text-gray-800 border-gray-200',
                        default => 'bg-gray-100 text-gray-800'
                    };
                @endphp
                <span class="inline-flex px-3 py-1 text-sm font-bold rounded-full border {{ $stColor }} shadow-sm">
                    Status: {{ mb_strtoupper($laporan->status) }}
                </span>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Vehicle Info -->
                <div class="col-span-1 border-r border-gray-200 pr-4">
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 border-b pb-2">Informasi Kendaraan</h4>
                    <div class="space-y-4">
                        <div>
                            <p class="text-xs text-gray-500">Nama Kendaraan</p>
                            <p class="font-bold text-gray-900 border border-gray-300 p-2 rounded mt-1 bg-gray-50">{{ $laporan->kendaraan->nama ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Nomor Registrasi</p>
                            <p class="font-bold text-gray-900">{{ $laporan->kendaraan->no_register ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Jenis/Model</p>
                            <p class="font-bold text-gray-900">{{ $laporan->kendaraan->jenis ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Incident Info -->
                <div class="col-span-1 md:col-span-2">
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 border-b pb-2">Rincian Insiden</h4>
                    
                    <div class="mb-4">
                        <p class="text-xs text-gray-500 mb-1">Tingkat Prioritas</p>
                        @php
                            $prioColor = match($laporan->tingkat_prioritas) {
                                'Tinggi' => 'bg-red-600 text-white shadow-sm',
                                'Sedang' => 'bg-yellow-500 text-white shadow-sm',
                                'Rendah' => 'bg-green-500 text-white shadow-sm',
                                default => 'bg-gray-500 text-white'
                            };
                        @endphp
                        <span class="px-3 py-1 inline-flex text-xs font-bold rounded-md {{ $prioColor }}">
                            {{ strtoupper($laporan->tingkat_prioritas) }}
                        </span>
                    </div>

                    <div class="mb-6">
                        <p class="text-xs text-gray-500 mb-1">Deskripsi Kerusakan</p>
                        <div class="bg-gray-50 border border-gray-200 rounded p-4 text-sm text-gray-800 whitespace-pre-wrap leading-relaxed">{{ $laporan->deskripsi }}</div>
                    </div>

                    <!-- Photos -->
                    <div class="mb-2">
                        <p class="text-xs text-gray-500 mb-2">Dokumentasi Foto Lampiran</p>
                        @if($laporan->foto && is_array($laporan->foto) && count($laporan->foto) > 0)
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                                @foreach($laporan->foto as $index => $fotoPath)
                                    <a href="{{ Storage::url($fotoPath) }}" target="_blank" class="block group relative rounded overflow-hidden shadow-sm hover:shadow-md transition">
                                        <div class="aspect-w-1 aspect-h-1 w-full bg-gray-200">
                                            <img src="{{ Storage::url($fotoPath) }}" alt="Foto Kerusakan {{ $index + 1 }}" class="object-cover w-full h-24 transform group-hover:scale-105 transition duration-300">
                                        </div>
                                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition flex items-center justify-center">
                                            <svg class="w-6 h-6 text-white opacity-0 group-hover:opacity-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                                            </svg>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-400 italic">Tidak ada foto dilampirkan.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Action Buttons Footer -->
        <div class="bg-gray-50 px-6 py-4 flex flex-col sm:flex-row justify-between items-center border-t border-gray-200">
            <div class="text-sm text-gray-500 mb-4 sm:mb-0">
                Data diperbarui terakhir pada {{ $laporan->updated_at->format('d M Y H:i:s') }}
            </div>
            
            <div class="flex gap-3">
                @if($laporan->status === 'Menunggu' && auth()->user()->hasAnyRole(['KepMek', 'Admin']))
                    <button wire:click="tolak" wire:confirm="Anda yakin menolak laporan ini? Kendaraan akan kembali ke status Siap Tempur." class="inline-flex px-4 py-2 bg-white border border-red-300 text-red-700 font-medium text-sm rounded-md hover:bg-red-50 focus:ring focus:ring-red-200 shadow-sm transition">
                        Tolak Laporan
                    </button>
                    <button wire:click="verifikasi" class="inline-flex px-4 py-2 bg-[#2D5A45] border border-transparent text-white font-medium text-sm rounded-md hover:bg-[#1B3A2D] focus:ring focus:ring-[#2D5A45] shadow-sm transition gap-2 items-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Verifikasi & Lanjutkan
                    </button>
                @endif

                @if($laporan->status === 'Diverifikasi')
                    <button wire:click="$dispatchTo('laporan-perbaikan-form', 'buatPerbaikan', { id_kerusakan: {{ $laporan->id }} })" class="inline-flex px-4 py-2 bg-[#C8A84B] border border-transparent text-[#1B3A2D] font-bold text-sm rounded-md hover:bg-yellow-500 focus:ring focus:ring-yellow-300 shadow shadow-yellow-200 gap-2 items-center transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Buat Laporan Perbaikan
                    </button>
                @endif
                
                @if(in_array($laporan->status, ['Dalam Proses', 'Selesai']) && $laporan->laporanPerbaikan)
                    <a href="{{ route('laporan-perbaikan.show', $laporan->laporanPerbaikan->id) }}" class="inline-flex px-4 py-2 bg-indigo-600 border border-transparent text-white font-medium text-sm rounded-md hover:bg-indigo-700 shadow-sm transition gap-2 items-center">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        Lihat Data Perbaikan Terkait
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Include Perbaikan Form component -->
    @livewire('laporan-perbaikan-form')
</div>
