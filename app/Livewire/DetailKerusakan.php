<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\LaporanKerusakan;

class DetailKerusakan extends Component
{
    public $laporanId;
    public $laporan;

    public function mount($laporanId)
    {
        $this->laporanId = $laporanId;
        $this->loadData();
    }

    public function loadData()
    {
        $this->laporan = LaporanKerusakan::with(['kendaraan', 'pelapor', 'laporanPerbaikan'])->findOrFail($this->laporanId);
    }

    public function verifikasi()
    {
        // Require role KepMek/Admin
        if (!auth()->user()->hasAnyRole(['KepMek', 'Admin'])) {
            abort(403, 'Akses ditolak.');
        }

        $this->laporan->update(['status' => 'Diverifikasi']);
        session()->flash('success', 'Laporan berhasil diverifikasi. Kini dapat diteruskan untuk Perbaikan.');
        $this->loadData();
    }

    public function tolak()
    {
        // Require role KepMek/Admin
        if (!auth()->user()->hasAnyRole(['KepMek', 'Admin'])) {
            abort(403, 'Akses ditolak.');
        }

        $this->laporan->update(['status' => 'Ditolak']);
        
        // Kembalikan status kendaraan menjadi Siap Tempur
        if ($this->laporan->kendaraan) {
            $this->laporan->kendaraan->update(['status' => 'Siap Tempur']);
        }

        session()->flash('warning', 'Laporan telah ditolak. Status kendaraan telah dikembalikan ke Siap Tempur.');
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.detail-kerusakan');
    }
}
