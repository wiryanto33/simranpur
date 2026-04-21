<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\LaporanKerusakan;
use App\Models\LaporanPerbaikan;
use App\Models\SukuCadang;
use App\Models\TransaksiSukuCadang;
use App\Models\User;
use App\Notifications\SystemNotification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;

class LaporanPerbaikanForm extends Component
{
    use WithFileUploads;

    public $showForm = false;
    public $isEdit = false;
    public $perbaikanId;
    public $idKerusakan;
    public $dataKerusakan;

    // Form fields
    public $tanggal_mulai;
    public $tanggal_selesai;
    public $deskripsi;
    public $fotos_hasil = [];
    public $fotos_existing = [];

    // Approved request info
    public $activeRequest;
    public $sukuCadangList = [];  // Display only

    protected $rules = [
        'tanggal_mulai'       => 'required|date',
        'tanggal_selesai'     => 'nullable|date|after_or_equal:tanggal_mulai',
        'deskripsi'           => 'required|string|min:10',
        'fotos_hasil'         => 'nullable|array|max:5',
        'fotos_hasil.*'       => 'image|max:2048',
        'sukuCadangList'      => 'nullable|array',
        'sukuCadangList.*.suku_cadang_id' => 'required|exists:suku_cadang,id',
        'sukuCadangList.*.jumlah'         => 'required|integer|min:1',
    ];

    #[\Livewire\Attributes\On('buatPerbaikan')]
    public function openForm($id_kerusakan)
    {
        $this->reset(['perbaikanId', 'isEdit', 'tanggal_mulai', 'tanggal_selesai', 'deskripsi', 'fotos_hasil', 'fotos_existing', 'sukuCadangList', 'activeRequest']);
        $this->resetValidation();

        $this->idKerusakan = $id_kerusakan;
        $this->dataKerusakan = LaporanKerusakan::with('kendaraan')->find($id_kerusakan);
        
        // Fetch approved spare part request
        $this->activeRequest = \App\Models\PermintaanSukuCadang::with('details.sukuCadang')
            ->where('laporan_kerusakan_id', $id_kerusakan)
            ->where('status', 'Approved')
            ->first();

        if ($this->activeRequest) {
            foreach ($this->activeRequest->details as $detail) {
                $this->sukuCadangList[] = [
                    'suku_cadang_id' => $detail->suku_cadang_id,
                    'nama' => $detail->sukuCadang->nama,
                    'jumlah' => $detail->jumlah,
                    'satuan' => $detail->sukuCadang->satuan
                ];
            }
        }

        $this->tanggal_mulai = now()->format('Y-m-d');
        $this->showForm = true;
    }

    #[\Livewire\Attributes\On('editPerbaikan')]
    public function editPerbaikan($id)
    {
        if (!auth()->user()->can('edit_laporan_perbaikan')) abort(403);

        $perbaikan = LaporanPerbaikan::with(['laporanKerusakan.kendaraan', 'transaksiSukuCadang'])->findOrFail($id);
        
        $this->resetValidation();
        $this->isEdit = true;
        $this->perbaikanId = $perbaikan->id;
        $this->idKerusakan = $perbaikan->laporan_kerusakan_id;
        $this->dataKerusakan = $perbaikan->laporanKerusakan;
        
        $this->tanggal_mulai = $perbaikan->tanggal_mulai->format('Y-m-d');
        $this->tanggal_selesai = $perbaikan->tanggal_selesai ? $perbaikan->tanggal_selesai->format('Y-m-d') : null;
        $this->deskripsi = $perbaikan->deskripsi;
        $this->fotos_existing = $perbaikan->foto_hasil ?? [];
        $this->fotos_hasil = [];

        $this->sukuCadangList = [];
        foreach ($perbaikan->transaksiSukuCadang as $trx) {
            $this->sukuCadangList[] = [
                'suku_cadang_id' => $trx->suku_cadang_id,
                'jumlah' => $trx->jumlah
            ];
        }

        $this->showForm = true;
    }

    public function addSukuCadang()
    {
        $this->sukuCadangList[] = ['suku_cadang_id' => '', 'jumlah' => 1];
    }

    public function removeSukuCadang($index)
    {
        array_splice($this->sukuCadangList, $index, 1);
    }

    public function removeFotoExisting($index)
    {
        array_splice($this->fotos_existing, $index, 1);
    }

    public function submit()
    {
        $this->validate();

        // Upload foto hasil perbaikan baru
        $newFotoPaths = [];
        if ($this->fotos_hasil) {
            foreach ($this->fotos_hasil as $foto) {
                $newFotoPaths[] = $foto->store('laporan_perbaikan/hasil', 'public');
            }
        }

        if ($this->isEdit) {
            $perbaikan = LaporanPerbaikan::findOrFail($this->perbaikanId);

            // Handle deletion of removed existing photos
            if (is_array($perbaikan->foto_hasil)) {
                $deleted = array_diff($perbaikan->foto_hasil, $this->fotos_existing);
                foreach ($deleted as $path) {
                    Storage::disk('public')->delete($path);
                }
            }

            // Rollback old spare parts stock before applying new ones
            foreach ($perbaikan->transaksiSukuCadang as $trx) {
                if ($trx->jenis === 'out') {
                    $trx->sukuCadang?->increment('stok', $trx->jumlah);
                }
                $trx->delete();
            }

            $perbaikan->update([
                'tanggal_mulai'   => $this->tanggal_mulai,
                'tanggal_selesai' => $this->tanggal_selesai,
                'deskripsi'       => $this->deskripsi,
                'foto_hasil'      => array_merge($this->fotos_existing, $newFotoPaths),
            ]);

            $message = 'Laporan perbaikan berhasil diperbarui.';
        } else {
            $perbaikan = LaporanPerbaikan::create([
                'laporan_kerusakan_id' => $this->idKerusakan,
                'mekanik_id'           => auth()->id(),
                'tanggal_mulai'        => $this->tanggal_mulai,
                'tanggal_selesai'      => $this->tanggal_selesai,
                'deskripsi'            => $this->deskripsi,
                'status'               => 'Menunggu Approval',
                'foto_hasil'           => $newFotoPaths,
            ]);

             // Update status laporan kerusakan → Selesai
            LaporanKerusakan::find($this->idKerusakan)?->update(['status' => 'Selesai']);

            // Kirim Notifikasi ke KepMek
            $kepMeks = User::role('KepMek')->get();
            $kendaraan = $this->dataKerusakan->kendaraan;
            Notification::send($kepMeks, new SystemNotification(
                'Perbaikan Menunggu Approval',
                'Perbaikan untuk kendaraan ' . ($kendaraan->nomor_ranpur ?? $kendaraan->nama) . ' telah selesai oleh ' . auth()->user()->name . ' dan menunggu approval.',
                route('laporan-perbaikan.index'),
                'info'
            ));
            
            $message = 'Laporan perbaikan berhasil dibuat. Menunggu approval KepMek.';
        }

        // Link the existing stock transactions to this repair report
        if ($this->activeRequest) {
            \App\Models\TransaksiSukuCadang::where('permintaan_id', $this->activeRequest->id)
                ->update(['laporan_perbaikan_id' => $perbaikan->id]);
        }

        $this->showForm = false;
        $this->dispatch('perbaikanSaved', message: $message);
    }

    public function render()
    {
        try {
            $allSukuCadang = \Illuminate\Support\Facades\Cache::remember('master_suku_cadang', now()->addDay(), function() {
                return SukuCadang::all();
            });
            if (!($allSukuCadang instanceof \Illuminate\Support\Collection)) {
                \Illuminate\Support\Facades\Cache::forget('master_suku_cadang');
                $allSukuCadang = SukuCadang::all();
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Cache::forget('master_suku_cadang');
            $allSukuCadang = SukuCadang::all();
        }

        return view('livewire.laporan-perbaikan-form', [
            'allSukuCadang' => $allSukuCadang
        ]);
    }
}
