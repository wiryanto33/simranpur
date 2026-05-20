<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Mekanik;

class MekanikForm extends Component
{
    public bool $showForm = false;
    public bool $isEdit = false;
    public ?int $mekanikId = null;

    public string $nama = '';
    public string $pangkat = '';
    public string $nrp = '';
    public string $jabatan = '';
    public string $no_hp = '';
    public string $status = 'Aktif';

    protected function rules(): array
    {
        return [
            'nama'    => 'required|string|max:100',
            'pangkat' => 'nullable|string|max:50',
            'nrp'     => 'nullable|string|max:30|unique:mekanik,nrp,' . ($this->mekanikId ?? 'NULL') . ',id',
            'jabatan' => 'nullable|string|max:100',
            'no_hp'   => 'nullable|string|max:20',
            'status'  => 'required|in:Aktif,Tidak Aktif',
        ];
    }

    protected array $messages = [
        'nama.required' => 'Nama mekanik wajib diisi.',
        'nrp.unique'    => 'NRP sudah terdaftar.',
    ];

    #[\Livewire\Attributes\On('createMekanik')]
    public function openCreate(): void
    {
        if (! auth()->user()->can('create_mekanik')) abort(403);
        $this->reset(['mekanikId', 'nama', 'pangkat', 'nrp', 'jabatan', 'no_hp']);
        $this->resetValidation();
        $this->status  = 'Aktif';
        $this->isEdit  = false;
        $this->showForm = true;
    }

    #[\Livewire\Attributes\On('editMekanik')]
    public function openEdit(int $id): void
    {
        if (! auth()->user()->can('edit_mekanik')) abort(403);
        $m = Mekanik::findOrFail($id);
        $this->resetValidation();
        $this->mekanikId = $m->id;
        $this->nama      = $m->nama;
        $this->pangkat   = $m->pangkat ?? '';
        $this->nrp       = $m->nrp ?? '';
        $this->jabatan   = $m->jabatan ?? '';
        $this->no_hp     = $m->no_hp ?? '';
        $this->status    = $m->status;
        $this->isEdit    = true;
        $this->showForm  = true;
    }

    public function submit(): void
    {
        $this->validate();

        $data = [
            'nama'    => $this->nama,
            'pangkat' => $this->pangkat ?: null,
            'nrp'     => $this->nrp ?: null,
            'jabatan' => $this->jabatan ?: null,
            'no_hp'   => $this->no_hp ?: null,
            'status'  => $this->status,
        ];

        if ($this->isEdit) {
            Mekanik::findOrFail($this->mekanikId)->update($data);
            $message = 'Data mekanik berhasil diperbarui.';
        } else {
            Mekanik::create($data);
            $message = 'Mekanik baru berhasil ditambahkan.';
        }

        $this->showForm = false;
        $this->dispatch('mekanikSaved', message: $message);
    }

    public function render()
    {
        return view('livewire.mekanik-form');
    }
}
