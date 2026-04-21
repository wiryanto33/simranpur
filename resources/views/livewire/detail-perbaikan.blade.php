<div>
    <div class="mb-4">
        <a href="{{ route('laporan-perbaikan.index') }}" class="inline-flex items-center text-sm text-[#2D5A45] hover:underline font-medium">
            <svg class="mr-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Daftar Perbaikan
        </a>
    </div>

    @php
        $lk = $perbaikan->laporanKerusakan;
        $kendaraan = $lk?->kendaraan;
    @endphp

    <!-- Header Card -->
    <div class="bg-white rounded-xl shadow border overflow-hidden mb-6">
        <div class="bg-[#1B3A2D] px-6 py-4 flex flex-col md:flex-row justify-between items-start md:items-center">
            <div>
                <h3 class="text-lg font-bold text-white uppercase tracking-wider">
                    Laporan Perbaikan — LP-{{ str_pad($perbaikan->id, 4, '0', STR_PAD_LEFT) }}
                </h3>
                <p class="text-xs text-[#C8A84B] mt-1">
                    Berdasarkan LK-{{ str_pad($lk?->id, 4, '0', STR_PAD_LEFT) }} | Kendaraan: {{ $kendaraan->nama ?? '-' }}
                </p>
            </div>
            <div class="mt-3 md:mt-0 flex gap-2 items-center">
                @php
                    $stColor = match($perbaikan->status) {
                        'Menunggu Approval' => 'bg-yellow-100 text-yellow-800',
                        'Disetujui'        => 'bg-green-100 text-green-800',
                        'Perlu Revisi'     => 'bg-red-100 text-red-800',
                        default            => 'bg-gray-100 text-gray-800',
                    };
                @endphp
                <span class="inline-flex px-3 py-1 text-sm font-bold rounded-full {{ $stColor }} border">
                    {{ $perbaikan->status }}
                </span>
                <a href="{{ route('laporan-perbaikan.cetak-pdf', $perbaikan->id) }}" target="_blank"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-white text-[#1B3A2D] border border-white text-sm font-semibold rounded hover:bg-gray-100 transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Cetak Berita Acara PDF
                </a>
            </div>
        </div>

        <div class="p-6 grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Left: Kendaraan & Kerusakan -->
            <div class="lg:col-span-1 space-y-5">
                <div>
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3 pb-2 border-b">Informasi Kendaraan</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between"><span class="text-gray-500">Nama</span><span class="font-semibold text-gray-800">{{ $kendaraan->nama ?? '-' }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">No. Registrasi</span><span class="font-semibold text-gray-800">{{ $kendaraan->no_register ?? '-' }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Jenis</span><span class="font-semibold text-gray-800">{{ $kendaraan->jenis ?? '-' }}</span></div>
                    </div>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3 pb-2 border-b">Tim Perbaikan</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between"><span class="text-gray-500">Mekanik</span><span class="font-semibold text-gray-800">{{ $perbaikan->mekanik->name ?? '-' }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Mulai</span><span class="font-semibold text-gray-800">{{ $perbaikan->tanggal_mulai?->format('d M Y') ?? '-' }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Selesai</span><span class="font-semibold text-gray-800 {{ !$perbaikan->tanggal_selesai ? 'text-orange-500' : '' }}">{{ $perbaikan->tanggal_selesai?->format('d M Y') ?? 'Belum selesai' }}</span></div>
                        @if($perbaikan->approvedBy)
                            <div class="flex justify-between"><span class="text-gray-500">Disetujui oleh</span><span class="font-semibold text-green-700">{{ $perbaikan->approvedBy->name }}</span></div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right: Deskripsi & Suku Cadang -->
            <div class="lg:col-span-2 space-y-5">
                <div>
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3 pb-2 border-b">Deskripsi Pekerjaan</h4>
                    <div class="bg-gray-50 border rounded p-4 text-sm text-gray-800 whitespace-pre-wrap leading-relaxed">{{ $perbaikan->deskripsi }}</div>
                </div>

                <!-- Suku Cadang -->
                <div>
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3 pb-2 border-b">Suku Cadang yang Digunakan</h4>
                    @php $transaksis = $perbaikan->transaksiSukuCadang; @endphp
                    @if($transaksis && $transaksis->count() > 0)
                        <table class="min-w-full text-sm border border-gray-200 rounded overflow-hidden">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs text-gray-500 font-semibold uppercase">Kode</th>
                                    <th class="px-4 py-2 text-left text-xs text-gray-500 font-semibold uppercase">Nama Suku Cadang</th>
                                    <th class="px-4 py-2 text-center text-xs text-gray-500 font-semibold uppercase">Jumlah</th>
                                    <th class="px-4 py-2 text-left text-xs text-gray-500 font-semibold uppercase">Satuan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach($transaksis as $t)
                                    <tr>
                                        <td class="px-4 py-2 font-mono text-xs text-gray-600">{{ $t->sukuCadang->kode ?? '-' }}</td>
                                        <td class="px-4 py-2 font-medium text-gray-800">{{ $t->sukuCadang->nama ?? '-' }}</td>
                                        <td class="px-4 py-2 text-center font-bold text-gray-800">{{ $t->jumlah }}</td>
                                        <td class="px-4 py-2 text-gray-600">{{ $t->sukuCadang->satuan ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-sm text-gray-400 italic">Tidak ada suku cadang yang dicatat untuk perbaikan ini.</p>
                    @endif
                </div>

                <!-- Foto Hasil -->
                <div>
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3 pb-2 border-b">Foto Hasil Perbaikan</h4>
                    @if($perbaikan->foto_hasil && count($perbaikan->foto_hasil) > 0)
                        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-3">
                            @foreach($perbaikan->foto_hasil as $foto)
                                <a href="{{ Storage::url($foto) }}" target="_blank" class="block group rounded overflow-hidden shadow-sm hover:shadow-md transition">
                                    <img src="{{ Storage::url($foto) }}" class="object-cover w-full h-24 transform group-hover:scale-105 transition duration-300">
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
</div>
