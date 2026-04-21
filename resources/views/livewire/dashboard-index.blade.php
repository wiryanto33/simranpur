<div class="space-y-6">
    <!-- WIDGET UNTUK SEMUA ROLE (STOK STATS & READINESS) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-gradient-to-br from-[#1B3A2D] to-[#2D5A45] rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-all duration-300">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-300 uppercase tracking-wider">Siap Tempur</p>
                    <h3 class="text-3xl font-bold mt-1">{{ $vehicleStats['Siap Tempur'] }}</h3>
                </div>
                <div class="p-2 bg-white/10 rounded-lg">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs text-green-300">
                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" /></svg>
                <span>Armada Siap Operasi</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-2xl transition-shadow duration-300">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Standby / Cadangan</p>
                    <h3 class="text-3xl font-bold mt-1 text-gray-800">{{ $vehicleStats['Standby'] }}</h3>
                </div>
                <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs text-blue-600 font-medium">
                Aktif dalam antrian operasional
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-2xl transition-shadow duration-300">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Dalam Perbaikan</p>
                    <h3 class="text-3xl font-bold mt-1 text-orange-600">{{ $vehicleStats['Perbaikan'] }}</h3>
                </div>
                <div class="p-2 bg-orange-50 rounded-lg text-orange-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs text-orange-600 font-medium">
                Sedang ditangani mekanik
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-2xl transition-shadow duration-300">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Tidak Layak</p>
                    <h3 class="text-3xl font-bold mt-1 text-red-600">{{ $vehicleStats['Tidak Layak'] }}</h3>
                </div>
                <div class="p-2 bg-red-50 rounded-lg text-red-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs text-red-600 font-medium">
                Membutuhkan tindakan segera
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Progress Bar Kesiapan -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 lg:col-span-1 flex flex-col justify-center">
            <h4 class="text-sm font-bold text-gray-700 uppercase mb-4 tracking-tighter">Persentase Kesiapan Armada</h4>
            <div class="relative pt-1">
                <div class="flex mb-2 items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-[#1B3A2D] bg-green-200">
                            Status Kesiapan
                        </span>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-semibold inline-block text-[#1B3A2D]">
                            {{ $fleetReadiness }}%
                        </span>
                    </div>
                </div>
                <div class="overflow-hidden h-4 mb-4 text-xs flex rounded bg-green-100 border border-green-200 shadow-inner">
                    <div style="width:{{ $fleetReadiness }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-gradient-to-r from-[#1B3A2D] to-[#C8A84B] transition-all duration-1000"></div>
                </div>
            </div>
            <p class="text-xs text-gray-500 italic mt-2">*Berdasarkan jumlah kendaraan status 'Siap Tempur'</p>
        </div>

        <!-- Jadwal Hari Ini -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 lg:col-span-2 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b flex justify-between items-center">
                <h4 class="text-sm font-bold text-gray-700 uppercase tracking-tighter">Jadwal Pemeliharaan Hari Ini</h4>
                <span class="px-2 py-0.5 rounded text-[10px] bg-[#1B3A2D] text-white font-bold uppercase">{{ now()->format('d M Y') }}</span>
            </div>
            <div class="max-h-48 overflow-y-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($todaySchedules as $js)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-3 whitespace-nowrap text-sm font-bold text-gray-900">{{ $js->kendaraan->nomor_ranpur }}</td>
                            <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-600">{{ $js->jenis_pemeliharaan }}</td>
                            <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-500">
                                @foreach($js->mekanik as $m)
                                    <span class="inline-block px-2 py-0.5 bg-gray-100 rounded-md text-[10px] mr-1">{{ $m->name }}</span>
                                @endforeach
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-right">
                                <span class="px-2 py-1 text-[10px] font-bold rounded bg-yellow-100 text-yellow-800">{{ $js->status }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-sm text-gray-400">Tidak ada jadwal pemeliharaan untuk hari ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @role('Admin|Komandan')
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6" x-data="{ labels: {{ json_encode($readinessHistory['labels']) }}, data: {{ json_encode($readinessHistory['data']) }} }">
        <!-- Grafik Kesiapan 30 Hari -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h4 class="text-sm font-bold text-gray-700 uppercase tracking-tighter mb-4">Grafik Kesiapan Ranpur (30 Hari Terakhir)</h4>
            <div class="h-64">
                <canvas id="readinessChart" wire:ignore></canvas>
            </div>
        </div>

        <!-- Rekap Kerusakan & Top Damage -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <h4 class="text-sm font-bold text-gray-700 uppercase tracking-tighter mb-4">Rekap Laporan Kerusakan Bulan Ini</h4>
                <div class="grid grid-cols-3 gap-4 text-center">
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <p class="text-xs text-gray-500 uppercase">Total</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $damageRecap['total'] }}</p>
                    </div>
                    <div class="p-4 bg-green-50 rounded-xl border border-green-100">
                        <p class="text-xs text-green-600 uppercase">Selesai</p>
                        <p class="text-2xl font-bold text-green-700">{{ $damageRecap['selesai'] }}</p>
                    </div>
                    <div class="p-4 bg-red-50 rounded-xl border border-red-100 animate-pulse">
                        <p class="text-xs text-red-600 uppercase">Pending</p>
                        <p class="text-2xl font-bold text-red-700">{{ $damageRecap['pending'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <h4 class="text-sm font-bold text-gray-700 uppercase tracking-tighter mb-4">Top 5 Kendaraan Sering Rusak</h4>
                <div class="space-y-3">
                    @foreach($topDamagedVehicles as $tdv)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded bg-red-100 flex items-center justify-center text-red-600 mr-3 text-xs font-bold">#{{ $loop->iteration }}</div>
                            <span class="text-sm font-medium text-gray-700">{{ $tdv->kendaraan->nama }} ({{ $tdv->kendaraan->nomor_ranpur }})</span>
                        </div>
                        <span class="text-sm font-bold text-red-600">{{ $tdv->total }} Kali</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Jadwal 7 Hari ke Depan -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b flex justify-between items-center">
            <h4 class="text-sm font-bold text-gray-700 uppercase tracking-tighter">Jadwal Pemeliharaan (7 Hari ke Depan)</h4>
            <a href="{{ route('jadwal.index') }}" class="text-xs text-[#1B3A2D] font-bold hover:underline">Kelola Semua &rarr;</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Ranpur</th>
                        <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Jenis</th>
                        <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach($upcomingSchedules as $us)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-600">{{ $us->tanggal->format('d M') }}</td>
                        <td class="px-6 py-3 whitespace-nowrap text-sm font-bold text-gray-800">{{ $us->kendaraan->nomor_ranpur }}</td>
                        <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-600">{{ $us->jenis_pemeliharaan }}</td>
                        <td class="px-6 py-3 whitespace-nowrap text-sm">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold border border-yellow-200 bg-yellow-50 text-yellow-700">{{ $us->status }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endrole

    @role('Admin|KepMek|Mekanik')
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Laporan Kerusakan Menunggu Verifikasi -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden ring-2 ring-red-500/20">
            <div class="px-6 py-4 bg-red-50 border-b flex justify-between items-center">
                <h4 class="text-sm font-bold text-red-800 uppercase tracking-tighter flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    Menunggu Verifikasi (Pending)
                </h4>
                <span class="text-[10px] font-bold text-red-600">PRIORITAS</span>
            </div>
            <div class="max-h-64 overflow-y-auto divide-y divide-gray-100">
                @forelse($pendingVerification as $pv)
                <div class="p-4 hover:bg-gray-50 flex justify-between items-center transition-colors">
                    <div>
                        <div class="text-sm font-bold text-gray-900 block">{{ $pv->kendaraan->nomor_ranpur }} - {{ $pv->kendaraan->nama }}</div>
                        <div class="text-xs text-gray-500 mt-1 line-clamp-1 italic">"{{ $pv->deskripsi }}"</div>
                    </div>
                    <a href="{{ route('laporan-kerusakan.index') }}" class="text-[10px] font-bold text-white bg-red-600 px-3 py-1.5 rounded-lg shadow-sm hover:bg-red-700">VERIFIKASI</a>
                </div>
                @empty
                <div class="p-10 text-center text-gray-400 text-sm italic">Tidak ada laporan kerusakan baru.</div>
                @endforelse
            </div>
        </div>

        <!-- Laporan Perbaikan Menunggu Approval -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden ring-2 ring-yellow-500/20">
            <div class="px-6 py-4 bg-yellow-50 border-b flex justify-between items-center">
                <h4 class="text-sm font-bold text-yellow-800 uppercase tracking-tighter flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" /></svg>
                    Approval Perbaikan Selesai
                </h4>
            </div>
            <div class="max-h-64 overflow-y-auto divide-y divide-gray-100">
                @forelse($pendingApproval as $pa)
                <div class="p-4 hover:bg-gray-50 flex justify-between items-center transition-colors">
                    <div>
                        <div class="text-sm font-bold text-gray-900">{{ $pa->laporanKerusakan->kendaraan->nomor_ranpur }}</div>
                        <div class="text-xs text-gray-600 mt-1 uppercase font-medium">Mekanik: {{ $pa->mekanik->name }}</div>
                    </div>
                    <a href="{{ route('laporan-perbaikan.index') }}" class="text-[10px] font-bold text-white bg-yellow-600 px-3 py-1.5 rounded-lg shadow-sm hover:bg-yellow-700 uppercase">Periksa Hasil</a>
                </div>
                @empty
                <div class="p-10 text-center text-gray-400 text-sm italic">Tidak ada perbaikan menunggu approval.</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Beban Kerja Mekanik -->
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <h4 class="text-sm font-bold text-gray-700 uppercase tracking-tighter mb-4">Beban Kerja Tim Mekanik (Active Jobs)</h4>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($mechanicWorkload as $mw)
            <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 flex items-center">
                <div class="w-10 h-10 rounded-full bg-[#1B3A2D] flex items-center justify-center text-white font-bold mr-4 text-sm shadow-md">
                    {{ substr($mw->name, 0, 1) }}
                </div>
                <div>
                    <h5 class="text-sm font-bold text-gray-800">{{ $mw->name }}</h5>
                    <div class="flex items-center">
                        @if($mw->active_jobs > 0)
                            <div class="animate-pulse bg-orange-500 w-2 h-2 rounded-full mr-2"></div>
                            <span class="text-xs text-orange-600 font-bold uppercase tracking-tighter">{{ $mw->active_jobs }} Menangani Tugas</span>
                        @else
                            <div class="bg-green-500 w-2 h-2 rounded-full mr-2"></div>
                            <span class="text-xs text-green-600 font-bold uppercase tracking-tighter">Standby</span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endrole

    @role('Admin|Logistik|Kepala Logistik')
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Stok Suku Cadang Menyehat -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden border-l-4 border-l-red-500">
            <div class="px-6 py-4 bg-gray-50 border-b flex justify-between items-center">
                <h4 class="text-sm font-bold text-red-600 uppercase tracking-tighter flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                    Stok Suku Cadang Menipis / Habis
                </h4>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($lowStockSpareparts as $sc)
                        <tr class="hover:bg-red-50 transition-colors">
                            <td class="px-6 py-3 text-sm font-medium text-gray-800">{{ $sc->nama }}</td>
                            <td class="px-6 py-3 text-sm text-center">
                                <span class="px-3 py-1 font-bold rounded-full {{ $sc->stok == 0 ? 'bg-red-100 text-red-800' : 'bg-orange-100 text-orange-800' }}">
                                    {{ $sc->stok }} {{ $sc->satuan }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-right">
                                <a href="{{ route('suku-cadang.index') }}" class="text-[10px] font-bold text-blue-700 hover:underline uppercase">Order Re-stock</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-10 text-center text-sm text-gray-400 italic">Semua stok suku cadang mencukupi.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Penggunaan Suku Cadang Terbanyak -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h4 class="text-sm font-bold text-gray-700 uppercase tracking-tighter mb-4">Top 5 Penggunaan Suku Cadang (Bulan Ini)</h4>
            <div class="space-y-4 pt-2">
                @foreach($sparepartUsage as $su)
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm font-medium text-gray-700">{{ $su->sukuCadang->nama }}</span>
                        <span class="text-sm font-bold text-gray-900">{{ $su->total }} Unit</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                         <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ rand(20, 90) }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endrole

    <!-- CUSTOM CHART SCRIPT -->
    @role('Admin|Komandan')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('livewire:initialized', () => {
             const ctx = document.getElementById('readinessChart');
             
             new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @js($readinessHistory['labels']),
                    datasets: [{
                        label: 'Persentase Kesiapan (%)',
                        data: @js($readinessHistory['data']),
                        fill: true,
                        borderColor: '#1B3A2D',
                        backgroundColor: 'rgba(27, 58, 45, 0.05)',
                        tension: 0.4,
                        borderWidth: 3,
                        pointBackgroundColor: '#C8A84B',
                        pointBorderColor: '#fff',
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: false,
                            min: 50,
                            max: 100,
                            ticks: { stepSize: 10 }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
             });
        });
    </script>
    @endrole

    <!-- OPERATOR DASHBOARD -->
    @if(auth()->user()->isOperator())
    <div class="mt-8">
        <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
            <svg class="w-6 h-6 mr-2 text-[#1B3A2D]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            Status Kendaraan Tugas Anda
        </h3>

        @if(!$operatorKendaraan)
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700 font-bold">Anda belum ditugaskan pada kendaraan manapun.</p>
                        <p class="text-xs text-yellow-600 mt-1">Silakan hubungi Admin atau Komandan untuk mendapatkan penugasan ranpur.</p>
                    </div>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Info Ranpur -->
                <div class="bg-white rounded-2xl shadow-lg border border-[#1B3A2D]/20 overflow-hidden md:col-span-1">
                    <div class="bg-[#1B3A2D] text-white p-4">
                        <h4 class="font-bold text-lg text-center">{{ $operatorKendaraan->nomor_ranpur }}</h4>
                        <p class="text-xs text-center text-gray-300">{{ $operatorKendaraan->nama }}</p>
                    </div>
                    <div class="p-6 text-center">
                        <p class="text-sm text-gray-500 mb-2 uppercase font-bold tracking-widest">Status Saat Ini</p>
                        <x-badge color="{{ $operatorKendaraan->status_badge }}" class="text-lg px-4 py-2">{{ $operatorKendaraan->status }}</x-badge>
                        
                        <div class="mt-6 flex justify-center">
                            <a href="{{ route('laporan-kerusakan.index') }}" class="text-sm px-6 py-2 bg-red-600 text-white rounded-md shadow hover:bg-red-700 transition">
                                Lapor Kerusakan
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Progress Laporan Aktif -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 md:col-span-2">
                    <h4 class="text-sm font-bold text-gray-700 uppercase tracking-tighter mb-4 border-b pb-2">Progres Perbaikan Berjalan</h4>
                    
                    @forelse($operatorActiveReports as $ar)
                        <div class="mb-4 last:mb-0 relative pl-4 border-l-2 border-orange-300">
                            <span class="absolute -left-1.5 top-1.5 w-3 h-3 rounded-full bg-orange-500 ring-4 ring-orange-100"></span>
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start pt-0.5">
                                <div>
                                    <h5 class="text-sm font-bold text-gray-800">{{ $ar->deskripsi }}</h5>
                                    <p class="text-xs text-gray-500 mt-1">Dilaporkan pada: {{ \Carbon\Carbon::parse($ar->tanggal)->format('d M Y') }}</p>
                                    @if($ar->laporanPerbaikan)
                                        <p class="text-xs text-[#1B3A2D] font-medium mt-1 uppercase">Ditangani oleh: {{ $ar->laporanPerbaikan->mekanik->name ?? 'Tim Mekanik' }}</p>
                                    @endif
                                </div>
                                <div class="mt-2 sm:mt-0">
                                    <span class="px-2 py-1 text-xs font-bold rounded-full bg-orange-100 text-orange-800 animate-pulse block text-center">
                                        {{ $ar->status }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center flex flex-col items-center">
                            <svg class="h-12 w-12 text-green-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <p class="text-sm font-bold text-gray-700">Tidak Ada Kerusakan Aktif</p>
                            <p class="text-xs text-gray-500 mt-1">Ranpur dalam kondisi baik dan tidak ada perbaikan yang tertunda.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Riwayat Perbaikan -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b">
                    <h4 class="text-sm font-bold text-gray-700 uppercase tracking-tighter">Riwayat Perbaikan Sebelumnya (Top 5)</h4>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-white">
                            <tr>
                                <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Tanggal Lapor</th>
                                <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Deskripsi Kerusakan</th>
                                <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Tindakan Perbaikan</th>
                                <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Mekanik</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($operatorRepairHistory as $rh)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ \Carbon\Carbon::parse($rh->tanggal)->format('d M Y') }}</td>
                                <td class="px-6 py-4 text-sm text-gray-800 font-medium max-w-xs truncate">{{ $rh->deskripsi }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">
                                    {{ $rh->laporanPerbaikan->tindakan ?? 'Selesai' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-[#1B3A2D] font-medium">
                                    {{ $rh->laporanPerbaikan->mekanik->name ?? '-' }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-400 italic">Belum ada riwayat kerusakan yang diselesaikan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
    @endif
</div>
