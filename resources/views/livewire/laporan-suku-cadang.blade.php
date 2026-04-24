<div>
    <x-page-header title="Laporan Penggunaan Suku Cadang" subtitle="Rekapitulasi mutasi dan penggunaan suku cadang">
        <x-slot name="actions">
            <a href="{{ route('laporan.export', ['type' => 'suku-cadang', 'format' => 'excel']) }}" class="flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700 transition">
                Export Kartu Stok (Excel)
            </a>
        </x-slot>
    </x-page-header>

    <x-card class="mt-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div>
                <x-input-label for="start" value="Mulai" />
                <x-text-input wire:model.live="start_date" id="start" type="date" class="mt-1 block w-full" />
            </div>
            <div>
                <x-input-label for="end" value="Selesai" />
                <x-text-input wire:model.live="end_date" id="end" type="date" class="mt-1 block w-full" />
            </div>
            <div>
                <x-input-label for="suku_cadang" value="Suku Cadang" />
                <select wire:model.live="suku_cadang_id" id="suku_cadang" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-[#1B3A2D] focus:ring-[#1B3A2D]">
                    <option value="">Semua Suku Cadang</option>
                    @foreach($sukuCadangs as $sc)
                        <option value="{{ $sc->id }}">{{ $sc->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <x-input-label for="jenis" value="Jenis Transaksi" />
                <select wire:model.live="jenis" id="jenis" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-[#1B3A2D] focus:ring-[#1B3A2D]">
                    <option value="">Semua</option>
                    <option value="in">Masuk (In)</option>
                    <option value="out">Keluar (Out)</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-6">
            <div class="p-4 bg-green-50 rounded-xl border border-green-100">
                <p class="text-xs text-green-600 uppercase font-bold">Total Suku Cadang Masuk</p>
                <p class="text-2xl font-black text-green-800">{{ $rekap['in'] ?? 0 }} <span class="text-xs font-normal">Unit</span></p>
            </div>
            <div class="p-4 bg-orange-50 rounded-xl border border-orange-100">
                <p class="text-xs text-orange-600 uppercase font-bold">Total Suku Cadang Keluar</p>
                <p class="text-2xl font-black text-orange-800">{{ $rekap['out'] ?? 0 }} <span class="text-xs font-normal">Unit</span></p>
            </div>
        </div>

        <x-table>
            <x-slot name="head">
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Suku Cadang</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
            </x-slot>
            <x-slot name="body">
                @foreach($transaksis as $t)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $t->tanggal->format('d/m/Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">{{ $t->sukuCadang->nama ?? 'Suku Cadang Terhapus' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $t->jenis == 'in' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }}">
                            {{ $t->jenis == 'in' ? 'MASUK' : 'KELUAR' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold {{ $t->jenis == 'in' ? 'text-green-600' : 'text-orange-600' }}">
                        {{ $t->jenis == 'in' ? '+' : '-' }}{{ $t->jumlah }} {{ $t->sukuCadang->satuan ?? '' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 italic">{{ $t->keterangan ?? '-' }}</td>
                </tr>
                @endforeach
            </x-slot>
        </x-table>
    </x-card>
</div>
