<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\LaporanPerbaikan;
use Barryvdh\DomPDF\Facade\Pdf;

class DetailPerbaikan extends Component
{
    public $perbaikanId;
    public $perbaikan;

    public function mount($perbaikanId)
    {
        $this->perbaikanId = $perbaikanId;
        $this->loadData();
    }

    public function loadData()
    {
        $this->perbaikan = LaporanPerbaikan::with([
            'laporanKerusakan.kendaraan',
            'laporanKerusakan.pelapor',
            'mekanik',
            'approvedBy',
            'transaksiSukuCadang.sukuCadang',
        ])->findOrFail($this->perbaikanId);
    }

    public function cetakPdf()
    {
        return redirect()->route('laporan-perbaikan.cetak-pdf', $this->perbaikanId);
    }

    public function render()
    {
        return view('livewire.detail-perbaikan');
    }
}
