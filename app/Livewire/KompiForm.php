<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Kompi;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class KompiForm extends Component
{
    use AuthorizesRequests;

    public $kompi_id;
    public $nama;
    public $kode;
    public $deskripsi;
    
    public $isEdit = false;
    public $showForm = false;

    protected function rules()
    {
        return [
            'nama' => 'required|string|max:255',
            'kode' => ['required', 'string', 'max:50', \Illuminate\Validation\Rule::unique('kompi')->ignore($this->kompi_id)],
            'deskripsi' => 'nullable|string',
        ];
    }

    protected $messages = [
        'nama.required' => 'Nama kompi wajib diisi.',
        'kode.required' => 'Kode kompi wajib diisi.',
        'kode.unique' => 'Kode kompi sudah digunakan.',
    ];

    #[\Livewire\Attributes\On('createKompi')]
    public function createKompi()
    {
        if(!auth()->user()->hasAnyRole(['Admin'])) {
            abort(403, 'Akses ditolak.');
        }
        $this->resetValidation();
        $this->reset(['kompi_id', 'nama', 'kode', 'deskripsi']);
        $this->isEdit = false;
        $this->showForm = true;
    }

    #[\Livewire\Attributes\On('editKompi')]
    public function editKompi($id)
    {
        if(!auth()->user()->hasAnyRole(['Admin'])) {
            abort(403, 'Akses ditolak.');
        }
        $this->resetValidation();
        $kompi = Kompi::findOrFail($id);
        
        $this->kompi_id = $kompi->id;
        $this->nama = $kompi->nama;
        $this->kode = $kompi->kode;
        $this->deskripsi = $kompi->deskripsi;
        
        $this->isEdit = true;
        $this->showForm = true;
    }

    public function submit()
    {
        $this->validate($this->rules());

        $data = [
            'nama' => $this->nama,
            'kode' => $this->kode,
            'deskripsi' => $this->deskripsi,
        ];

        if ($this->isEdit) {
            Kompi::where('id', $this->kompi_id)->update($data);
            $message = 'Data kompi berhasil diperbarui.';
        } else {
            Kompi::create($data);
            $message = 'Kompi baru berhasil ditambahkan.';
        }

        $this->showForm = false;
        $this->dispatch('kompiSaved', message: $message);
    }

    public function render()
    {
        return view('livewire.kompi-form');
    }
}
