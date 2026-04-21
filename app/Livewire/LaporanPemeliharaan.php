<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\JadwalPemeliharaan;
use App\Models\Kendaraan;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class LaporanPemeliharaan extends Component
{
    public $start_date;
    public $end_date;
    public $kendaraan_id;
    public $mekanik_id;
    public $status;

    public function mount()
    {
        $this->start_date = now()->startOfMonth()->format('Y-m-d');
        $this->end_date = now()->format('Y-m-d');
    }

    public function render()
    {
        $query = JadwalPemeliharaan::with(['kendaraan', 'mekanik'])
            ->whereBetween('tanggal', [$this->start_date, $this->end_date]);

        if ($this->kendaraan_id) {
            $query->where('kendaraan_id', $this->kendaraan_id);
        }

        if ($this->mekanik_id) {
            $query->whereHas('mekanik', function($q) {
                $q->where('users.id', $this->mekanik_id);
            });
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        try {
            $kendaraans = \Illuminate\Support\Facades\Cache::remember('master_kendaraan', now()->addDay(), fn() => Kendaraan::all());
            if (!($kendaraans instanceof \Illuminate\Support\Collection)) {
                \Illuminate\Support\Facades\Cache::forget('master_kendaraan');
                $kendaraans = Kendaraan::all();
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Cache::forget('master_kendaraan');
            $kendaraans = Kendaraan::all();
        }

        return view('livewire.laporan-pemeliharaan', [
            'jadwals' => $query->latest('tanggal')->get(),
            'kendaraans' => $kendaraans,
            'mekaniks' => User::role('Mekanik')->get()
        ]);
    }
}
