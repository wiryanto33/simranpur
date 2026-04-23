<div>
    <x-page-header title="Laporan Kesiapan Kendaraan" subtitle="Rekapitulasi kesiapan armada ranpur">
        <x-slot name="actions">
            <div class="flex space-x-2">
                <a href="{{ route('laporan.export', ['type' => 'kesiapan', 'format' => 'pdf']) }}?kompi_id={{ $kompi_id }}&jenis={{ $jenis_kendaraan }}" 
                   class="flex items-center px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    Export PDF
                </a>
            </div>
        </x-slot>
    </x-page-header>

    <x-card class="mt-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div>
                <x-input-label for="kompi" value="Satuan (Kompi)" />
                <select wire:model.live="kompi_id" id="kompi" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-[#1B3A2D] focus:ring-[#1B3A2D]">
                    <option value="">Semua Kompi</option>
                    @foreach(collect($kompis) as $k)
                        @if(is_object($k))
                            <option value="{{ $k->id }}">{{ $k->nama }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div>
                <x-input-label for="jenis" value="Jenis Kendaraan" />
                <select wire:model.live="jenis_kendaraan" id="jenis" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-[#1B3A2D] focus:ring-[#1B3A2D]">
                    <option value="">Semua Jenis</option>
                    @foreach($jenisList as $jl)
                        <option value="{{ $jl }}">{{ $jl }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
            <div class="lg:col-span-1 space-y-4">
                @foreach($stats as $status => $count)
                    <div class="p-4 rounded-xl border border-gray-100 flex justify-between items-center bg-gray-50">
                        <span class="text-xs font-bold uppercase text-gray-500">{{ $status }}</span>
                        <span class="text-xl font-black {{ $status == 'Siap Tempur' ? 'text-green-600' : ($status == 'Tidak Layak' ? 'text-red-600' : 'text-gray-800') }}">
                            {{ $count }}
                        </span>
                    </div>
                @endforeach
            </div>
            <div class="lg:col-span-3">
                <div class="h-64 flex items-center justify-center bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                    <canvas id="kesiapanPieChart" wire:ignore></canvas>
                </div>
            </div>
        </div>

        <x-table>
            <x-slot name="head">
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor Ranpur</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kompi</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
            </x-slot>
            <x-slot name="body">
                @foreach($kendaraans as $ken)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $loop->iteration }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">{{ $ken->nomor_ranpur }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $ken->nama }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $ken->kompi->nama ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <x-badge :color="$ken->status_badge">{{ $ken->status }}</x-badge>
                    </td>
                </tr>
                @endforeach
            </x-slot>
        </x-table>
    </x-card>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('livewire:initialized', () => {
            let chart;
            const initChart = () => {
                const ctx = document.getElementById('kesiapanPieChart');
                if (chart) chart.destroy();
                
                chart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: @js(array_keys($stats)),
                        datasets: [{
                            data: @js(array_values($stats)),
                            backgroundColor: ['#10B981', '#3B82F6', '#F59E0B', '#EF4444'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'right' }
                        }
                    }
                });
            };

            initChart();
            Livewire.on('chartUpdated', () => initChart());
        });
    </script>
</div>
