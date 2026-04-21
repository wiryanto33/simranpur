<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Kendaraan;
use App\Models\Kompi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class LaporanKesiapan extends Component
{
    public $kompi_id;
    public $jenis_kendaraan;
    public $start_date;
    public $end_date;

    public function mount()
    {
        $this->start_date = now()->startOfMonth()->format('Y-m-d');
        $this->end_date = now()->format('Y-m-d');
    }

    public function render()
    {
        $query = Kendaraan::with('kompi');

        if ($this->kompi_id) {
            $query->where('kompi_id', $this->kompi_id);
        }

        if ($this->jenis_kendaraan) {
            $query->where('jenis', $this->jenis_kendaraan);
        }

        $kendaraans = $query->get();

        $statsCollection = $kendaraans->groupBy('status')->map->count();
        $statuses = ['Siap Tempur', 'Standby', 'Perbaikan', 'Tidak Layak'];
        $stats = [];
        foreach ($statuses as $s) {
            $stats[$s] = $statsCollection[$s] ?? 0;
        }

        try {
            $kompis = \Illuminate\Support\Facades\Cache::remember('master_kompi', now()->addDay(), fn() => Kompi::all());
            if (!($kompis instanceof \Illuminate\Support\Collection)) {
                \Illuminate\Support\Facades\Cache::forget('master_kompi');
                $kompis = Kompi::all();
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Cache::forget('master_kompi');
            $kompis = Kompi::all();
        }

        return view('livewire.laporan-kesiapan', [
            'kendaraans' => $kendaraans,
            'stats' => $stats,
            'kompis' => $kompis,
            'jenisList' => Kendaraan::distinct()->pluck('jenis')
        ]);
    }
}
