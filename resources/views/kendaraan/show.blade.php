<x-app-layout>
    <x-page-header title="Detail Kendaraan Tempur" subtitle="{{ $kendaraan->nomor_ranpur }} - {{ $kendaraan->nama }}">
        <x-slot name="actions">
            <a href="{{ route('kendaraan.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
                Kembali
            </a>
            @role('Admin|KepMek')
            <button onclick="alert('Ubah Status Fitur: to be implemented')" class="inline-flex items-center ml-3 px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#C8A84B] hover:bg-yellow-600 focus:outline-none">
                Ubah Status
            </button>
            @endrole
        </x-slot>
    </x-page-header>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Informasi Utama -->
        <div class="md:col-span-1 space-y-6">
            <x-card title="Informasi Kendaraan">
                @if($kendaraan->foto)
                    <div class="mb-4">
                        <img src="{{ asset('storage/'.$kendaraan->foto) }}" alt="{{ $kendaraan->nama }}" class="w-full h-auto rounded-lg shadow-sm border border-gray-200">
                    </div>
                @else
                    <div class="mb-4 w-full h-48 bg-gray-100 flex items-center justify-center rounded-lg border border-gray-200">
                        <svg class="w-12 h-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                @endif
                
                <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Nomor Ranpur</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $kendaraan->nomor_ranpur }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Kompi</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $kendaraan->kompi->nama ?? '-' }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Jenis</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $kendaraan->jenis }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Tahun Pembuatan</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $kendaraan->tahun }}</dd>
                    </div>
                    <div class="sm:col-span-2 pt-2 border-t border-gray-100">
                        <dt class="text-sm font-medium text-gray-500 mb-1">Status Kesiapan</dt>
                        <dd>
                            <x-badge :color="$kendaraan->status_badge">{{ $kendaraan->status }}</x-badge>
                        </dd>
                    </div>
                </dl>
            </x-card>
        </div>

        <!-- Detail Tabs -->
        <div class="md:col-span-2" x-data="{ tab: 'pemeliharaan' }">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <button @click="tab = 'pemeliharaan'" :class="tab === 'pemeliharaan' ? 'border-[#1B3A2D] text-[#1B3A2D]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Jadwal Pemeliharaan
                    </button>
                    <button @click="tab = 'kerusakan'" :class="tab === 'kerusakan' ? 'border-[#1B3A2D] text-[#1B3A2D]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Riwayat Kerusakan
                    </button>
                    <button @click="tab = 'sukucadang'" :class="tab === 'sukucadang' ? 'border-[#1B3A2D] text-[#1B3A2D]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Suku Cadang Terpakai
                    </button>
                </nav>
            </div>

            <div class="mt-6 bg-white shadow-sm rounded-lg border border-gray-100 p-6 min-h-[300px]">
                <div x-show="tab === 'pemeliharaan'" x-cloak>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Jadwal Pemeliharaan Mendatang</h3>
                    @if($kendaraan->jadwalPemeliharaan->count() > 0)
                        <!-- Tampilkan tabel jadwal jika ada -->
                        <ul class="divide-y divide-gray-200">
                            @foreach($kendaraan->jadwalPemeliharaan as $jadwal)
                                <li class="py-4 flex justify-between items-center">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $jadwal->jenis_pemeliharaan }}</p>
                                        <p class="text-sm text-gray-500">Pj: {{ $jadwal->mekanik->pluck('name')->join(', ') ?: '-' }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }}</p>
                                        <span class="text-xs font-semibold px-2 py-1 bg-yellow-100 text-yellow-800 rounded">{{ $jadwal->status }}</span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <x-empty-state title="Belum Ada Jadwal" description="Belum ada jadwal pemeliharaan terencana untuk ranpur ini." />
                    @endif
                </div>
                <div x-show="tab === 'kerusakan'" x-cloak>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Riwayat Laporan Kerusakan</h3>
                    @if($kendaraan->laporanKerusakan->count() > 0)
                        <ul class="divide-y divide-gray-200 mt-2">
                             @foreach($kendaraan->laporanKerusakan as $laporan)
                                 <li class="py-4">
                                     <p class="text-sm font-medium text-red-600">{{ $laporan->deskripsi }}</p>
                                     <p class="text-sm text-gray-500">🗓 {{ \Carbon\Carbon::parse($laporan->tanggal)->format('d M Y') }} | 👮 {{ $laporan->pelapor->name ?? '-' }}</p>
                                 </li>
                             @endforeach
                        </ul>
                    @else
                        <x-empty-state title="Belum Ada Kerusakan" description="Ranpur ini dalam kondisi prima tanpa sejarah kerusakan." />
                    @endif
                </div>
                <div x-show="tab === 'sukucadang'" x-cloak>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Penggunaan Suku Cadang</h3>
                    <x-empty-state title="Belum Ada Data" description="Data penggunaan suku cadang belum tersedia." />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
