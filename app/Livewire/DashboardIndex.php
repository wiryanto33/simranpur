<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Kendaraan;
use App\Models\JadwalPemeliharaan;
use App\Models\LaporanKerusakan;
use App\Models\LaporanPerbaikan;
use App\Models\SukuCadang;
use App\Models\TransaksiSukuCadang;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardIndex extends Component
{
    public $vehicleStats = [];
    public $fleetReadiness = 0;
    public $todaySchedules = [];
    
    // Operator
    public $operatorKendaraan = null;
    public $operatorActiveReports = [];
    public $operatorRepairHistory = [];
    
    // Admin & Komandan
    public $readinessHistory = [];
    public $damageRecap = [];
    public $topDamagedVehicles = [];
    public $upcomingSchedules = [];

    // KepMek
    public $pendingVerification = [];
    public $pendingApproval = [];
    public $mechanicWorkload = [];

    // Logistik
    public $lowStockSpareparts = [];
    public $sparepartUsage = [];

    public function mount()
    {
        $this->loadCommonStats();
        
        $user = auth()->user();
        if ($user->hasAnyRole(['Admin', 'Komandan'])) {
            $this->loadAdminStats();
        }
        
        if ($user->hasAnyRole(['KepMek', 'Admin', 'Mekanik'])) {
            $this->loadKepMekStats();
        }
        
        if ($user->hasAnyRole(['Logistik', 'Kepala Logistik', 'Admin'])) {
            $this->loadLogistikStats();
        }

        if ($user->isOperator()) {
            $this->loadOperatorStats($user);
        }
    }

    private function loadCommonStats()
    {
        try {
            $stats = \Illuminate\Support\Facades\Cache::remember('dashboard_common_stats', now()->addMinutes(15), function() {
                $vehicleStats = Kendaraan::select('status', DB::raw('count(*) as total'))
                    ->groupBy('status')
                    ->pluck('total', 'status')
                    ->toArray();

                $statuses = ['Siap Tempur', 'Standby', 'Perbaikan', 'Tidak Layak'];
                foreach ($statuses as $status) {
                    if (!isset($vehicleStats[$status])) {
                        $vehicleStats[$status] = 0;
                    }
                }

                $totalVehicles = Kendaraan::count();
                $fleetReadiness = $totalVehicles > 0 
                    ? round(($vehicleStats['Siap Tempur'] / $totalVehicles) * 100)
                    : 0;
                    
                return [
                    'vehicleStats' => $vehicleStats,
                    'fleetReadiness' => $fleetReadiness
                ];
            });

            if (!is_array($stats) || !isset($stats['vehicleStats']) || !isset($stats['fleetReadiness'])) {
                \Illuminate\Support\Facades\Cache::forget('dashboard_common_stats');
                $stats = ['vehicleStats' => ['Siap Tempur' => 0, 'Standby' => 0, 'Perbaikan' => 0, 'Tidak Layak' => 0], 'fleetReadiness' => 0];
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Cache::forget('dashboard_common_stats');
            $stats = ['vehicleStats' => ['Siap Tempur' => 0, 'Standby' => 0, 'Perbaikan' => 0, 'Tidak Layak' => 0], 'fleetReadiness' => 0];
        }

        $this->vehicleStats = $stats['vehicleStats'];
        $this->fleetReadiness = $stats['fleetReadiness'];

        $this->todaySchedules = JadwalPemeliharaan::with(['kendaraan', 'mekanik'])
            ->whereDate('tanggal', today())
            ->get();
    }

    private function loadAdminStats()
    {
        try {
            $this->readinessHistory = \Illuminate\Support\Facades\Cache::remember('dashboard_admin_readiness', now()->addHour(), function() {
                return [
                    'labels' => collect(range(29, 0))->reverse()->map(fn($d) => now()->subDays($d)->format('d M'))->values()->toArray(),
                    'data' => collect(range(29, 0))->map(fn($d) => rand(70, 95))->values()->toArray(), 
                ];
            });
            if (!is_array($this->readinessHistory)) {
                 \Illuminate\Support\Facades\Cache::forget('dashboard_admin_readiness');
                 $this->readinessHistory = ['labels' => [], 'data' => []];
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Cache::forget('dashboard_admin_readiness');
            $this->readinessHistory = ['labels' => [], 'data' => []];
        }

        try {
            $monthStats = \Illuminate\Support\Facades\Cache::remember('dashboard_admin_month_stats', now()->addMinutes(30), function() {
                return [
                    'total' => LaporanKerusakan::whereMonth('tanggal', now()->month)->count(),
                    'selesai' => LaporanKerusakan::whereMonth('tanggal', now()->month)->where('status', 'Selesai')->count(),
                    'pending' => LaporanKerusakan::whereMonth('tanggal', now()->month)->whereIn('status', ['Pending', 'Proses'])->count(),
                ];
            });
            if (!is_array($monthStats)) {
                \Illuminate\Support\Facades\Cache::forget('dashboard_admin_month_stats');
                $monthStats = ['total' => 0, 'selesai' => 0, 'pending' => 0];
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Cache::forget('dashboard_admin_month_stats');
            $monthStats = ['total' => 0, 'selesai' => 0, 'pending' => 0];
        }
        
        $this->damageRecap = $monthStats;

        $this->topDamagedVehicles = LaporanKerusakan::select('kendaraan_id', DB::raw('count(*) as total'))
            ->with('kendaraan:id,nama,nomor_ranpur')
            ->groupBy('kendaraan_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $this->upcomingSchedules = JadwalPemeliharaan::with(['kendaraan', 'mekanik'])
            ->where('tanggal', '>', today())
            ->where('tanggal', '<=', today()->addDays(7))
            ->orderBy('tanggal')
            ->get();
    }

    private function loadKepMekStats()
    {
        $this->pendingVerification = LaporanKerusakan::with('kendaraan')
            ->where('status', 'Pending')
            ->latest()
            ->get();

        $this->pendingApproval = LaporanPerbaikan::with(['laporanKerusakan.kendaraan', 'mekanik'])
            ->where('status', 'Menunggu Approval')
            ->latest()
            ->get();

        $this->mechanicWorkload = User::role('Mekanik')
            ->withCount(['laporanPerbaikan as active_jobs' => function($q) {
                $q->whereIn('status', ['Proses', 'Menunggu Approval']);
            }])
            ->get();
    }

    private function loadLogistikStats()
    {
        $this->lowStockSpareparts = SukuCadang::where('stok', '<=', 5)
            ->orderBy('stok')
            ->get();

        $this->sparepartUsage = TransaksiSukuCadang::select('suku_cadang_id', DB::raw('sum(jumlah) as total'))
            ->with('sukuCadang:id,nama')
            ->where('jenis', 'out')
            ->whereMonth('tanggal', now()->month)
            ->groupBy('suku_cadang_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
    }

    private function loadOperatorStats($user)
    {
        $this->operatorKendaraan = $user->kendaraanTugas;
        $kendaraanId = $user->kendaraan_tugas_id;

        if ($kendaraanId) {
            $this->operatorActiveReports = LaporanKerusakan::with(['laporanPerbaikan.mekanik'])
                ->where('kendaraan_id', $kendaraanId)
                ->whereNotIn('status', ['Selesai', 'Ditolak'])
                ->latest('tanggal')
                ->get();
            
            $this->operatorRepairHistory = LaporanKerusakan::with(['laporanPerbaikan.mekanik', 'pelapor'])
                ->where('kendaraan_id', $kendaraanId)
                ->where('status', 'Selesai')
                ->latest('tanggal')
                ->limit(5)
                ->get();
        }
    }

    public function render()
    {
        return view('livewire.dashboard-index');
    }
}
