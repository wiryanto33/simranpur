<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\SukuCadang;
use Illuminate\Support\Facades\Storage;

class SukuCadangForm extends Component
{
    use WithFileUploads;

    public $showForm = false;
    public $isEdit = false;
    public $scId;

    // Fields
    public $kode;
    public $nama;
    public $satuan;
    public $stok = 0;
    public $stok_minimum = 0;
    public $lokasi;
    public $foto;
    public $fotoPreview; // existing foto URL when editing

    protected function rules()
    {
        return [
            'kode'         => 'required|string|max:50|unique:suku_cadang,kode,' . $this->scId,
            'nama'         => 'required|string|max:255',
            'satuan'       => 'required|string|max:50',
            'stok'         => 'required|integer|min:0',
            'stok_minimum' => 'required|integer|min:0',
            'lokasi'       => 'nullable|string|max:255',
            'foto'         => 'nullable|image|max:2048',
        ];
    }

    #[\Livewire\Attributes\On('buatSukuCadang')]
    public function openCreate()
    {
        $this->resetValidation();
        $this->reset(['scId','kode','nama','satuan','stok','stok_minimum','lokasi','foto','fotoPreview']);
        $this->stok = 0;
        $this->stok_minimum = 0;
        $this->isEdit = false;
        $this->showForm = true;
    }

    #[\Livewire\Attributes\On('editSukuCadang')]
    public function openEdit($id)
    {
        $this->resetValidation();
        $sc = SukuCadang::findOrFail($id);
        $this->scId         = $sc->id;
        $this->kode         = $sc->kode;
        $this->nama         = $sc->nama;
        $this->satuan       = $sc->satuan;
        $this->stok         = $sc->stok;
        $this->stok_minimum = $sc->stok_minimum;
        $this->lokasi       = $sc->lokasi;
        $this->fotoPreview  = $sc->foto ? Storage::url($sc->foto) : null;
        $this->foto         = null;
        $this->isEdit = true;
        $this->showForm = true;
    }

    public function submit()
    {
        $this->validate();

        $fotoPath = null;

        if ($this->isEdit) {
            $sc = SukuCadang::findOrFail($this->scId);

            // Upload foto baru jika ada
            if ($this->foto) {
                // Hapus foto lama
                if ($sc->foto) Storage::disk('public')->delete($sc->foto);
                $fotoPath = $this->foto->store('suku_cadang', 'public');
            }

            $sc->update([
                'kode'         => $this->kode,
                'nama'         => $this->nama,
                'satuan'       => $this->satuan,
                'stok'         => $this->stok,
                'stok_minimum' => $this->stok_minimum,
                'lokasi'       => $this->lokasi,
                ...(($fotoPath !== null) ? ['foto' => $fotoPath] : []),
            ]);
            $message = 'Data suku cadang berhasil diperbarui.';
        } else {
            if ($this->foto) {
                $fotoPath = $this->foto->store('suku_cadang', 'public');
            }

            SukuCadang::create([
                'kode'         => $this->kode,
                'nama'         => $this->nama,
                'satuan'       => $this->satuan,
                'stok'         => $this->stok,
                'stok_minimum' => $this->stok_minimum,
                'lokasi'       => $this->lokasi,
                'foto'         => $fotoPath,
            ]);
            $message = 'Suku cadang baru berhasil ditambahkan.';
        }

        \Illuminate\Support\Facades\Cache::forget('master_suku_cadang');
        \Illuminate\Support\Facades\Cache::forget('master_suku_cadang_available');

        $this->showForm = false;
        $this->dispatch('sukuCadangSaved', message: $message);
    }

    public function render()
    {
        try {
            $satuanList = \Illuminate\Support\Facades\Cache::remember('master_satuan', now()->addYear(), function() {
                return ['Pcs', 'Set', 'Bottle', 'Litre', 'Kg', 'Roll', 'Box', 'Unit'];
            });
            if (!is_array($satuanList)) {
                \Illuminate\Support\Facades\Cache::forget('master_satuan');
                $satuanList = ['Pcs', 'Set', 'Bottle', 'Litre', 'Kg', 'Roll', 'Box', 'Unit'];
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Cache::forget('master_satuan');
            $satuanList = ['Pcs', 'Set', 'Bottle', 'Litre', 'Kg', 'Roll', 'Box', 'Unit'];
        }

        return view('livewire.suku-cadang-form', [
            'satuanList' => $satuanList
        ]);
    }
}
