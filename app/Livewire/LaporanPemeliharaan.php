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

        $kendaraans = Kendaraan::orderBy('nomor_ranpur')->get();

        return view('livewire.laporan-pemeliharaan', [
            'jadwals' => $query->latest('tanggal')->get(),
            'kendaraans' => $kendaraans,
            'mekaniks' => User::role('Mekanik')->get()
        ]);
    }
}
