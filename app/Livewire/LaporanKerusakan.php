<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\LaporanKerusakan as LaporanKerusakanModel;
use App\Models\Kendaraan;

class LaporanKerusakan extends Component
{
    public $start_date;
    public $end_date;
    public $kendaraan_id;
    public $prioritas;
    public $status_filter;

    public function mount()
    {
        $this->start_date = now()->startOfMonth()->format('Y-m-d');
        $this->end_date   = now()->format('Y-m-d');

        // Jika Operator: kunci filter ke ranpur yang ditugaskan
        if (auth()->user()->isOperator()) {
            $this->kendaraan_id = auth()->user()->kendaraan_tugas_id;
        }
    }

    public function render()
    {
        $user  = auth()->user();
        $query = LaporanKerusakanModel::with(['kendaraan', 'laporanPerbaikan', 'pelapor'])
            ->whereBetween('tanggal', [$this->start_date, $this->end_date]);

        // PEMBATASAN OPERATOR: hanya tampilkan laporan ranpur sendiri
        if ($user->isOperator()) {
            $kendaraanTugasId = $user->kendaraan_tugas_id;

            if ($kendaraanTugasId) {
                // Hanya laporan dari ranpur yang ditugaskan
                $query->where('kendaraan_id', $kendaraanTugasId);
            } else {
                // Operator belum ditugaskan ke ranpur → tidak tampilkan apapun
                $query->whereRaw('1 = 0');
            }
        } else {
            // Role lain: filter manual kendaraan jika dipilih
            if ($this->kendaraan_id) {
                $query->where('kendaraan_id', $this->kendaraan_id);
            }
        }

        if ($this->prioritas) {
            $query->where('tingkat_prioritas', $this->prioritas);
        }

        if ($this->status_filter) {
            $query->where('status', $this->status_filter);
        }

        $kerusakans = $query->latest('tanggal')->get();

        // Hitung rata-rata waktu perbaikan
        $fixedReports = $kerusakans->filter(
            fn($k) => $k->status === 'Selesai' && $k->laporanPerbaikan && $k->laporanPerbaikan->tanggal_selesai
        );
        $avgTime = 0;
        if ($fixedReports->count() > 0) {
            $totalDays = $fixedReports->sum(
                fn($k) => $k->tanggal->diffInDays($k->laporanPerbaikan->tanggal_selesai)
            );
            $avgTime = round($totalDays / $fixedReports->count(), 1);
        }

        // Daftar kendaraan untuk dropdown filter
        // Operator hanya melihat ranpur sendiri, role lain lihat semua
        $kendaraanList = $user->isOperator()
            ? Kendaraan::where('id', $user->kendaraan_tugas_id)->get()
            : Kendaraan::orderBy('nomor_ranpur')->get();

        return view('livewire.laporan-kerusakan', [
            'kerusakans'    => $kerusakans,
            'kendaraans'    => $kendaraanList,
            'avgTime'       => $avgTime,
            'isOperator'    => $user->isOperator(),
            'ranpurTugas'   => $user->isOperator() ? $user->detail?->kendaraan : null,
        ]);
    }
}
