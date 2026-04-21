<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\LaporanPerbaikan;
use App\Models\User;
use App\Notifications\SystemNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class LaporanPerbaikanIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = '';

    public $confirmingDeletion = false;
    public $idToDelete = null;

    protected $queryString = [
        'search'       => ['except' => ''],
        'filterStatus' => ['except' => ''],
    ];

    #[\Livewire\Attributes\On('perbaikanSaved')]
    public function handleRefresh($message = null)
    {
        if ($message) session()->flash('message', $message);
        $this->resetPage();
    }

    public function approve($id)
    {
        if (!auth()->user()->can('edit_laporan_perbaikan')) abort(403);

        $perbaikan = LaporanPerbaikan::with('laporanKerusakan.kendaraan')->findOrFail($id);
        $perbaikan->update([
            'status'      => 'Disetujui',
            'approved_by' => auth()->id(),
        ]);
         if ($perbaikan->laporanKerusakan) {
            $perbaikan->laporanKerusakan->update(['status' => 'Selesai']);
            $perbaikan->laporanKerusakan->kendaraan?->update(['status' => 'Siap Tempur']);

            // Kirim Notifikasi ke Mekanik & Pelapor/Operator
            $pelapor = $perbaikan->laporanKerusakan->pelapor;
            $mekanik = $perbaikan->mekanik;
            $kendaraan = $perbaikan->laporanKerusakan->kendaraan;

            Notification::send($mekanik, new SystemNotification(
                'Perbaikan Disetujui',
                'Laporan perbaikan Anda untuk ' . ($kendaraan->nomor_ranpur ?? $kendaraan->nama) . ' telah disetujui.',
                route('laporan-perbaikan.index'),
                'success'
            ));

            if ($pelapor) {
                Notification::send($pelapor, new SystemNotification(
                    'Kendaraan Siap Tempur',
                    'Perbaikan ranpur ' . ($kendaraan->nomor_ranpur ?? $kendaraan->nama) . ' telah selesai. Unit kembali siap operasional.',
                    '#',
                    'success'
                ));
            }

            // Kirim Notifikasi ke Komandan
            $komandans = User::role('Komandan')->get();
            Notification::send($komandans, new SystemNotification(
                'Status Kendaraan Berubah',
                'Status kendaraan ' . ($kendaraan->nomor_ranpur ?? $kendaraan->nama) . ' kembali Siap Tempur.',
                route('kendaraan.index'),
                'success'
            ));
        }

        session()->flash('message', 'Laporan perbaikan disetujui. Kendaraan kembali ke status Siap Tempur.');
        $this->resetPage();
    }

     public function kembalikan($id)
    {
        if (!auth()->user()->can('edit_laporan_perbaikan')) abort(403);

        $perbaikan = LaporanPerbaikan::with('laporanKerusakan.kendaraan')->findOrFail($id);
        $perbaikan->update(['status' => 'Perlu Revisi']);
        
        Notification::send($perbaikan->mekanik, new SystemNotification(
            'Revisi Laporan Perbaikan',
            'Laporan perbaikan ' . ($perbaikan->laporanKerusakan->kendaraan->nomor_ranpur ?? $perbaikan->laporanKerusakan->kendaraan->nama) . ' butuh revisi.',
            route('laporan-perbaikan.index'),
            'warning'
        ));

        session()->flash('message', 'Laporan dikembalikan ke mekanik untuk direvisi.');
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $this->confirmingDeletion = true;
        $this->idToDelete = $id;
    }

    public function delete()
    {
        if (!auth()->user()->can('delete_laporan_perbaikan')) abort(403);

        $perbaikan = LaporanPerbaikan::with(['laporanKerusakan', 'transaksiSukuCadang.sukuCadang'])->findOrFail($this->idToDelete);

        // Rollback stok suku cadang yang telah dipakai
        foreach ($perbaikan->transaksiSukuCadang as $trx) {
            if ($trx->jenis === 'out') {
                $trx->sukuCadang?->increment('stok', $trx->jumlah);
            }
            $trx->delete();
        }

        // Hapus foto hasil dari storage
        if ($perbaikan->foto_hasil && is_array($perbaikan->foto_hasil)) {
            foreach ($perbaikan->foto_hasil as $path) {
                Storage::disk('public')->delete($path);
            }
        }

        // Kembalikan status laporan kerusakan → Diverifikasi (siap dibuatkan perbaikan ulang)
        $perbaikan->laporanKerusakan?->update(['status' => 'Diverifikasi']);

        $perbaikan->delete();

        $this->confirmingDeletion = false;
        $this->idToDelete = null;
        session()->flash('message', 'Laporan perbaikan berhasil dihapus. Stok suku cadang dikembalikan.');
    }

    public function render()
    {
        $query = LaporanPerbaikan::with(['laporanKerusakan.kendaraan', 'mekanik', 'approvedBy']);

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        return view('livewire.laporan-perbaikan-index', [
            'perbaikans' => $query->latest()->paginate(10),
        ]);
    }
}
