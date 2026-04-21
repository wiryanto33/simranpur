<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PermintaanSukuCadang;
use App\Models\SukuCadang;
use App\Models\TransaksiSukuCadang;
use App\Models\User;
use App\Notifications\SystemNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class PermintaanSukuCadangIndex extends Component
{
    use WithPagination;

    public $filterStatus = '';
    public $confirmingApproval = false;
    public $confirmingRejection = false;
    public $activePermintaanId;
    public $activePermintaan;
    public $alasanPenolakan = '';

    public function updatingFilterStatus() { $this->resetPage(); }

    public function viewDetails($id)
    {
        $this->activePermintaanId = $id;
        $this->activePermintaan = PermintaanSukuCadang::with(['details.sukuCadang', 'laporanKerusakan.kendaraan', 'mekanik'])->findOrFail($id);
    }

    public function showApprove($id)
    {
        $this->activePermintaanId = $id;
        $this->activePermintaan = PermintaanSukuCadang::with('details.sukuCadang')->findOrFail($id);
        $this->confirmingApproval = true;
    }

    public function approve()
    {
        if (!auth()->user()->can('approve_permintaan_suku_cadang')) abort(403);

        $permintaan = $this->activePermintaan;

        // Final stock check
        foreach ($permintaan->details as $detail) {
            if ($detail->sukuCadang->stok < $detail->jumlah) {
                session()->flash('error', "Stok {$detail->sukuCadang->nama} tidak cukup untuk menyetujui permintaan ini.");
                $this->confirmingApproval = false;
                return;
            }
        }

        DB::transaction(function () use ($permintaan) {
            $permintaan->update([
                'status' => 'Approved',
                'checker_id' => auth()->id(),
                'tanggal_approval' => now(),
            ]);

            foreach ($permintaan->details as $detail) {
                // Kurangi stok
                $detail->sukuCadang->decrement('stok', $detail->jumlah);

                // Buat Transaksi Suku Cadang (Keluar)
                TransaksiSukuCadang::create([
                    'suku_cadang_id' => $detail->suku_cadang_id,
                    'user_id' => auth()->id(), // Pihak logistik yang memproses
                    'laporan_perbaikan_id' => null, // Akan diupdate saat laporan perbaikan dibuat
                    'permintaan_id' => $permintaan->id,
                    'jenis' => 'out',
                     'jumlah' => $detail->jumlah,
                    'keterangan' => 'Pemakaian perbaikan (Req: #'.$permintaan->id.')',
                    'tanggal' => now(),
                ]);

                // Hitung stok setelah pengurangan
                $sc = $detail->sukuCadang;
                $sc->refresh();
                if ($sc->stok <= $sc->stok_minimum) {
                    $notifUsers = User::role(['Logistik', 'Admin'])->get();
                    Notification::send($notifUsers, new SystemNotification(
                        'Stok Suku Cadang Menipis',
                        "Stok {$sc->nama} kritis! Sisa: {$sc->stok} {$sc->satuan}.",
                        route('suku-cadang.index', ['search' => $sc->nama]),
                        'danger'
                    ));
                }
            }

             // Update status laporan kerusakan → Siap Diperbaiki
            $permintaan->laporanKerusakan?->update(['status' => 'Siap Diperbaiki']);

            // Kirim Notifikasi ke Mekanik
            $kendaraan = $permintaan->laporanKerusakan->kendaraan;
            Notification::send($permintaan->mekanik, new SystemNotification(
                'Permintaan Suku Cadang Disetujui',
                'Suku cadang untuk kendaraan ' . ($kendaraan->nomor_ranpur ?? $kendaraan->nama) . ' telah tersedia. Anda dapat memulai perbaikan.',
                route('laporan-perbaikan.index'),
                'success'
            ));
        });

        $this->confirmingApproval = false;
        session()->flash('message', 'Permintaan disetujui. Stok telah dikurangi dan kendaraan siap diperbaiki.');
    }

    public function showReject($id)
    {
        $this->activePermintaanId = $id;
        $this->confirmingRejection = true;
        $this->alasanPenolakan = '';
    }

    public function reject()
    {
        if (!auth()->user()->can('approve_permintaan_suku_cadang')) abort(403);

        $permintaan = PermintaanSukuCadang::findOrFail($this->activePermintaanId);
        
        DB::transaction(function () use ($permintaan) {
            $permintaan->update([
                'status' => 'Rejected',
                'checker_id' => auth()->id(),
                'alasan_penolakan' => $this->alasanPenolakan,
                'tanggal_approval' => now(),
            ]);

             // Kembalikan status kerusakan → Diverifikasi (agar bisa diajukan ulang jika revisi)
            $permintaan->laporanKerusakan?->update(['status' => 'Diverifikasi']);

            // Kirim Notifikasi ke Mekanik
            $kendaraan = $permintaan->laporanKerusakan->kendaraan;
            Notification::send($permintaan->mekanik, new SystemNotification(
                'Permintaan Suku Cadang Ditolak',
                'Permintaan suku cadang untuk ' . ($kendaraan->nomor_ranpur ?? $kendaraan->nama) . ' ditolak. Alasan: ' . ($this->alasanPenolakan ?: '-'),
                route('permintaan-suku-cadang.index', ['filterStatus' => 'Rejected']),
                'danger'
            ));
        });

        $this->confirmingRejection = false;
        session()->flash('message', 'Permintaan ditolak.');
    }

    public function render()
    {
        $query = PermintaanSukuCadang::with(['laporanKerusakan.kendaraan', 'mekanik', 'checker', 'details.sukuCadang']);

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        return view('livewire.permintaan-suku-cadang-index', [
            'requests' => $query->latest()->paginate(10),
        ]);
    }
}
