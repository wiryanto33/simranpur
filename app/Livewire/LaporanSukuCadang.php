<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TransaksiSukuCadang;
use App\Models\SukuCadang;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class LaporanSukuCadang extends Component
{
    public $start_date;
    public $end_date;
    public $suku_cadang_id;
    public $jenis;

    public function mount()
    {
        $this->start_date = now()->startOfMonth()->format('Y-m-d');
        $this->end_date = now()->format('Y-m-d');
    }

    public function render()
    {
        $query = TransaksiSukuCadang::with('sukuCadang')
            ->whereBetween('tanggal', [$this->start_date, $this->end_date]);

        if ($this->suku_cadang_id) {
            $query->where('suku_cadang_id', $this->suku_cadang_id);
        }

        if ($this->jenis) {
            $query->where('jenis', $this->jenis);
        }

        $transaksis = $query->latest('tanggal')->get();

        $rekap = TransaksiSukuCadang::select('jenis', DB::raw('sum(jumlah) as total'))
            ->whereBetween('tanggal', [$this->start_date, $this->end_date])
            ->groupBy('jenis')
            ->pluck('total', 'jenis');

        try {
            $sukuCadangList = \Illuminate\Support\Facades\Cache::remember('master_suku_cadang', now()->addDay(), fn() => SukuCadang::all());
            if (!($sukuCadangList instanceof \Illuminate\Support\Collection)) {
                \Illuminate\Support\Facades\Cache::forget('master_suku_cadang');
                $sukuCadangList = SukuCadang::all();
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Cache::forget('master_suku_cadang');
            $sukuCadangList = SukuCadang::all();
        }

        return view('livewire.laporan-suku-cadang', [
            'transaksis' => $query->latest('tanggal')->get(),
            'sukuCadangs' => $sukuCadangList,
            'rekap' => $rekap
        ]);
    }
}
