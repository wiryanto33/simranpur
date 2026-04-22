<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Kendaraan;
use App\Models\LaporanKerusakan;
use App\Models\User;
use App\Notifications\SystemNotification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;

class LaporanKerusakanForm extends Component
{
    use WithFileUploads;

    public $showForm = false;
    public $isEdit = false;
    public $laporanId;

    public $kendaraan_id;
    public $deskripsi;
    public $tingkat_prioritas = 'Sedang';
    public $fotos = [];
    public $fotosExisting = []; // path foto lama saat edit

    protected function rules()
    {
        return [
            'kendaraan_id'      => 'required|exists:kendaraan,id',
            'deskripsi'         => 'required|string|min:10',
            'tingkat_prioritas' => 'required|in:Rendah,Sedang,Tinggi',
            'fotos'             => 'nullable|array|max:5',
            'fotos.*'           => 'image|max:2048',
        ];
    }

    #[\Livewire\Attributes\On('buatLaporan')]
    public function openCreate()
    {
        $this->reset(['laporanId', 'kendaraan_id', 'deskripsi', 'tingkat_prioritas', 'fotos', 'fotosExisting']);
        $this->resetValidation();
        $this->tingkat_prioritas = 'Sedang';
        $this->isEdit = false;
        $this->showForm = true;

        if (auth()->user()->isOperator()) {
            $this->kendaraan_id = auth()->user()->kendaraan_tugas_id;
        }
    }

    #[\Livewire\Attributes\On('editLaporan')]
    public function openEdit($id)
    {
        if (!auth()->user()->can('edit_laporan_kerusakan')) abort(403);

        $laporan = LaporanKerusakan::findOrFail($id);
        $this->resetValidation();
        $this->laporanId        = $laporan->id;
        $this->kendaraan_id     = $laporan->kendaraan_id;
        $this->deskripsi        = $laporan->deskripsi;
        $this->tingkat_prioritas = $laporan->tingkat_prioritas;
        $this->fotosExisting    = $laporan->foto ?? [];
        $this->fotos            = [];
        $this->isEdit = true;
        $this->showForm = true;
    }

    public function removeFotoExisting($index)
    {
        array_splice($this->fotosExisting, $index, 1);
    }

    public function submit()
    {
        $this->validate();

        // Upload foto baru
        $newPaths = [];
        foreach ($this->fotos as $foto) {
            $newPaths[] = $foto->store('laporan_kerusakan', 'public');
        }

        if ($this->isEdit) {
            $laporan = LaporanKerusakan::findOrFail($this->laporanId);

            // Foto yang dihapus (ada di lama tapi tidak di existing saat ini)
            if (is_array($laporan->foto)) {
                $deleted = array_diff($laporan->foto, $this->fotosExisting);
                foreach ($deleted as $path) {
                    Storage::disk('public')->delete($path);
                }
            }

            $mergedFotos = array_merge($this->fotosExisting, $newPaths);

            $laporan->update([
                'kendaraan_id'      => $this->kendaraan_id,
                'deskripsi'         => $this->deskripsi,
                'tingkat_prioritas' => $this->tingkat_prioritas,
                'foto'              => $mergedFotos,
            ]);
            $message = 'Laporan kerusakan berhasil diperbarui.';
        } else {
            $laporan = LaporanKerusakan::create([
                'kendaraan_id'      => $this->kendaraan_id,
                'pelapor_id'        => auth()->id(),
                'tanggal'           => now(),
                'deskripsi'         => $this->deskripsi,
                'tingkat_prioritas' => $this->tingkat_prioritas,
                'status'            => 'Menunggu',
                'foto'              => $newPaths,
            ]);

            // Otomatis update status kendaraan → Perbaikan
             Kendaraan::find($this->kendaraan_id)?->update(['status' => 'Perbaikan']);
            
            // Kirim Notifikasi ke Kepala Mekanik
            $kepMeks = User::role('KepMek')->get();
            $kendaraan = Kendaraan::find($this->kendaraan_id);
            Notification::send($kepMeks, new SystemNotification(
                'Laporan Kerusakan Baru',
                'Laporan baru untuk kendaraan ' . ($kendaraan->nomor_ranpur ?? $kendaraan->nama) . ' oleh ' . auth()->user()->name,
                route('laporan-kerusakan.index'),
                'warning'
            ));

            // Kirim Notifikasi ke Komandan
            $komandans = User::role('Komandan')->get();
            Notification::send($komandans, new SystemNotification(
                'Status Kendaraan Berubah',
                'Status kendaraan ' . ($kendaraan->nomor_ranpur ?? $kendaraan->nama) . ' berubah menjadi Perbaikan.',
                route('kendaraan.index'),
                'danger'
            ));

            $message = 'Laporan kerusakan berhasil disubmit. Status kendaraan diubah ke Perbaikan.';
        }

        $this->showForm = false;
        $this->dispatch('laporanSaved', message: $message);
    }

    public function render()
    {
        $user = auth()->user();
        
        $listKendaraan = $user->isOperator()
            ? Kendaraan::where('id', $user->kendaraan_tugas_id)->get()
            : Kendaraan::where('status', 'Siap Tempur')->orderBy('nomor_ranpur')->get();

        return view('livewire.laporan-kerusakan-form', [
            'kendaraans' => $listKendaraan,
            'isOperator' => $user->isOperator()
        ]);
    }
}
