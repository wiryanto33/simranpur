<div>
    <x-page-header title="Laporan Kerusakan & Perbaikan" subtitle="Rekapitulasi dumas dan tindak lanjut perbaikan">
        <x-slot name="actions">
            <div class="flex space-x-2">
                <a href="{{ route('laporan.export', ['type' => 'kerusakan', 'format' => 'excel']) }}" class="flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700 transition">Excel</a>
                <a href="{{ route('laporan.export', ['type' => 'kerusakan', 'format' => 'pdf']) }}" class="flex items-center px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 transition">PDF</a>
            </div>
        </x-slot>
    </x-page-header>

    <x-card class="mt-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div>
                <x-input-label for="start" value="Mulai" />
                <x-text-input wire:model.live="start_date" id="start" type="date" class="mt-1 block w-full" />
            </div>
            <div>
                <x-input-label for="end" value="Selesai" />
                <x-text-input wire:model.live="end_date" id="end" type="date" class="mt-1 block w-full" />
            </div>
            <div>
                <x-input-label for="kendaraan" value="Kendaraan" />
                @if($isOperator)
                    <div class="mt-1 block w-full py-2 px-3 bg-gray-100 border border-gray-300 rounded-md shadow-sm text-gray-700 sm:text-sm">
                        {{ collect($kendaraans)->first()?->nomor_ranpur ?? 'Tidak ada ranpur ditugaskan' }}
                    </div>
                @else
                    <select wire:model.live="kendaraan_id" id="kendaraan" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-[#1B3A2D] focus:ring-[#1B3A2D]">
                        <option value="">Semua Kendaraan</option>
                        @foreach($kendaraans as $k)
                            <option value="{{ $k->id }}">{{ $k->nomor_ranpur }}</option>
                        @endforeach
                    </select>
                @endif
            </div>
        </div>

        <div class="mb-6 p-4 bg-blue-50 rounded-xl border border-blue-100 flex items-center justify-between">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg text-blue-600 mr-4">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div>
                    <p class="text-sm text-blue-700 font-medium">Rata-rata Waktu Penyelesaian Perbaikan</p>
                    <p class="text-xs text-blue-500">Dihitung dari tanggal laporan hingga tanggal perbaikan selesai</p>
                </div>
            </div>
            <div class="text-3xl font-black text-blue-800">{{ $avgTime }} <span class="text-sm font-normal">Hari</span></div>
        </div>

        <x-table>
            <x-slot name="head">
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Lapor</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ranpur</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi Kerusakan</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durasi</th>
            </x-slot>
            <x-slot name="body">
                @foreach($kerusakans as $k)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $k->tanggal->format('d/m/Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-bold text-gray-900">{{ $k->kendaraan->nama ?? '-' }}</div>
                        <div class="text-xs text-gray-500">{{ $k->kendaraan->nomor_ranpur ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ Str::limit($k->deskripsi, 50) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <x-badge color="red">{{ $k->status }}</x-badge>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        @if($k->status == 'Selesai' && $k->laporanPerbaikan?->tanggal_selesai)
                            {{ $k->tanggal->diffInDays($k->laporanPerbaikan->tanggal_selesai) }} Hari
                        @else
                            -
                        @endif
                    </td>
                </tr>
                @endforeach
            </x-slot>
        </x-table>
    </x-card>
</div>
