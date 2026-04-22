<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\LaporanKerusakan;
use App\Models\SukuCadang;
use App\Models\PermintaanSukuCadang;
use App\Models\PermintaanSukuCadangDetail;
use App\Models\User;
use App\Notifications\SystemNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class PermintaanSukuCadangForm extends Component
{
    public $showModal = false;
    public $laporanId;
    public $laporan;
    public $keterangan = '';
    public $items = []; // [['suku_cadang_id' => '', 'jumlah' => 1]]

    protected $listeners = ['buatPermintaan'];

    public function buatPermintaan($laporanId)
    {
        $this->laporanId = $laporanId;
        $this->laporan = LaporanKerusakan::with('kendaraan')->findOrFail($laporanId);
        $this->reset(['keterangan', 'items']);
        $this->addItem();
        $this->showModal = true;
    }

    public function addItem()
    {
        $this->items[] = ['suku_cadang_id' => '', 'jumlah' => 1];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function submit()
    {
        $this->validate([
            'items' => 'required|array|min:1',
            'items.*.suku_cadang_id' => 'required|exists:suku_cadang,id',
            'items.*.jumlah' => 'required|integer|min:1',
        ]);

        // Check stock before submitting
        foreach ($this->items as $item) {
            $sc = SukuCadang::find($item['suku_cadang_id']);
            if ($sc && $sc->stok < $item['jumlah']) {
                $this->addError("items", "Stok {$sc->nama} tidak mencukupi (Tersedia: {$sc->stok})");
                return;
            }
        }

        DB::transaction(function () {
            $permintaan = PermintaanSukuCadang::create([
                'laporan_kerusakan_id' => $this->laporanId,
                'mekanik_id'           => auth()->id(),
                'status'               => 'Pending',
                'keterangan'           => $this->keterangan,
                'tanggal_permintaan'   => now(),
            ]);

            foreach ($this->items as $item) {
                PermintaanSukuCadangDetail::create([
                    'permintaan_id'  => $permintaan->id,
                    'suku_cadang_id' => $item['suku_cadang_id'],
                    'jumlah'         => $item['jumlah'],
                ]);
            }

             // Update status laporan kerusakan
            $this->laporan->update(['status' => 'Menunggu Suku Cadang']);

            // Kirim Notifikasi ke Logistik
            $logistikUsers = User::role('Logistik')->get();
            Notification::send($logistikUsers, new SystemNotification(
                'Permintaan Suku Cadang Baru',
                'Ada permintaan suku cadang baru untuk kendaraan ' . ($this->laporan->kendaraan->nomor_ranpur ?? $this->laporan->kendaraan->nama) . ' oleh ' . auth()->user()->name,
                route('permintaan-suku-cadang.index'),
                'info'
            ));
        });

        $this->showModal = false;
        $this->dispatch('laporanSaved', message: 'Permintaan suku cadang berhasil diajukan.');
    }

    public function render()
    {
        $sukuCadangList = SukuCadang::where('stok', '>', 0)->orderBy('nama')->get();

        return view('livewire.permintaan-suku-cadang-form', [
            'sukuCadangs' => $sukuCadangList,
        ]);
    }
}
