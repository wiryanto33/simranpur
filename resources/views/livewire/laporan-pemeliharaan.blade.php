<div>
    <x-page-header title="Laporan Pemeliharaan Berkala" subtitle="Rekapitulasi jadwal pemeliharaan kendaraan">
        <x-slot name="actions">
            <a href="{{ route('laporan.export', ['type' => 'pemeliharaan', 'format' => 'excel']) }}?start={{ $start_date }}&end={{ $end_date }}" 
               class="flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700 transition">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                Export Excel
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
                <x-input-label for="kendaraan" value="Kendaraan" />
                <select wire:model.live="kendaraan_id" id="kendaraan" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-[#1B3A2D] focus:ring-[#1B3A2D]">
                    <option value="">Semua Kendaraan</option>
                    @foreach(collect($kendaraans) as $k)
                        @if(is_object($k))
                            <option value="{{ $k->id }}">{{ $k->nomor_ranpur }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div>
                <x-input-label for="status" value="Status" />
                <select wire:model.live="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-[#1B3A2D] focus:ring-[#1B3A2D]">
                    <option value="">Semua Status</option>
                    <option value="Terjadwal">Terjadwal</option>
                    <option value="Sedang Dikerjakan">Sedang Dikerjakan</option>
                    <option value="Selesai">Selesai</option>
                    <option value="Terlambat">Terlambat</option>
                </select>
            </div>
        </div>

        <x-table>
            <x-slot name="head">
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ranpur</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mekanik</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
            </x-slot>
            <x-slot name="body">
                @foreach($jadwals as $j)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $j->tanggal->format('d/m/Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">{{ $j->kendaraan->nomor_ranpur }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $j->jenis_pemeliharaan }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $j->mekanik->pluck('name')->implode(', ') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <x-badge color="blue">{{ $j->status }}</x-badge>
                    </td>
                </tr>
                @endforeach
            </x-slot>
        </x-table>
    </x-card>
</div>
