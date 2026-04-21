<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\JadwalPemeliharaan;
use App\Models\Kendaraan;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class JadwalForm extends Component
{
    use AuthorizesRequests;

    public $jadwal_id;
    public $kendaraan_id;
    public $tanggal;
    public $estimasi_hari = 1;
    public $jenis_pemeliharaan;
    public $mekanik_ids = []; // Array for multiple mechanics
    public $keterangan;
    public $status = 'Terjadwal';

    public $checklist = []; // array of items
    
    public $isEdit = false;
    public $showForm = false;

    protected function rules()
    {
        return [
            'kendaraan_id' => 'required|exists:kendaraan,id',
            'tanggal' => 'required|date',
            'estimasi_hari' => 'required|integer|min:1',
            'jenis_pemeliharaan' => 'required|string|max:255',
            'mekanik_ids' => 'required|array|min:1',
            'mekanik_ids.*' => 'exists:users,id',
            'keterangan' => 'nullable|string',
            'status' => 'required|string',
            'checklist' => 'array',
            'checklist.*.task' => 'required|string|max:255',
        ];
    }

    public function mount()
    {
        $this->checklist = []; // initialize
    }

    public function addChecklistItem()
    {
        $this->checklist[] = ['task' => '', 'is_done' => false];
    }

    public function removeChecklistItem($index)
    {
        unset($this->checklist[$index]);
        $this->checklist = array_values($this->checklist);
    }

    #[\Livewire\Attributes\On('createJadwal')]
    public function createJadwal()
    {
        if(!auth()->user()->hasAnyRole(['Admin', 'KepMek'])) {
            abort(403, 'Akses ditolak.');
        }
        $this->resetValidation();
        $this->reset(['jadwal_id', 'kendaraan_id', 'tanggal', 'estimasi_hari', 'jenis_pemeliharaan', 'mekanik_ids', 'keterangan']);
        $this->mekanik_ids = [];
        $this->status = 'Terjadwal';
        $this->checklist = [['task' => 'Cek Mesin', 'is_done' => false]]; // default
        $this->isEdit = false;
        $this->showForm = true;
    }

    #[\Livewire\Attributes\On('editJadwal')]
    public function editJadwal($id)
    {
        if(!auth()->user()->hasAnyRole(['Admin', 'KepMek'])) {
            abort(403, 'Akses ditolak.');
        }
        $this->resetValidation();
        $jadwal = JadwalPemeliharaan::with('mekanik')->findOrFail($id);
        
        $this->jadwal_id = $jadwal->id;
        $this->kendaraan_id = $jadwal->kendaraan_id;
        $this->tanggal = $jadwal->tanggal->format('Y-m-d');
        $this->estimasi_hari = $jadwal->estimasi_hari;
        $this->jenis_pemeliharaan = $jadwal->jenis_pemeliharaan;
        $this->mekanik_ids = $jadwal->mekanik->pluck('id')->map(fn($id) => (string)$id)->toArray();
        $this->keterangan = $jadwal->keterangan;
        $this->status = $jadwal->status;

        $rawChecklist = is_array($jadwal->checklist) ? $jadwal->checklist : [];
        $this->checklist = array_map(function($item) {
            if (is_string($item)) {
                return ['task' => $item, 'is_done' => false];
            }
            return $item;
        }, $rawChecklist);

        if(count($this->checklist) == 0) {
            $this->addChecklistItem();
        }
        
        $this->isEdit = true;
        $this->showForm = true;
    }

    public function submit()
    {
        $this->validate($this->rules());

        $data = [
            'kendaraan_id' => $this->kendaraan_id,
            'tanggal' => $this->tanggal,
            'estimasi_hari' => $this->estimasi_hari,
            'jenis_pemeliharaan' => $this->jenis_pemeliharaan,
            'keterangan' => $this->keterangan,
            'status' => $this->status,
            'checklist' => $this->checklist,
        ];

        if ($this->isEdit) {
            $jadwal = JadwalPemeliharaan::findOrFail($this->jadwal_id);
            $jadwal->update($data);
            $jadwal->mekanik()->sync($this->mekanik_ids);
            $message = 'Data jadwal berhasil diperbarui.';
        } else {
            $jadwal = JadwalPemeliharaan::create($data);
            $jadwal->mekanik()->sync($this->mekanik_ids);
            $message = 'Jadwal baru berhasil ditambahkan.';
        }

        $this->showForm = false;
        $this->dispatch('jadwalSaved', message: $message);
    }

    public function render()
    {
        return view('livewire.jadwal-form', [
            'kendaraans' => Kendaraan::orderBy('nomor_ranpur')->get(),
            'mekaniks' => User::role('Mekanik')->get(),
        ]);
    }
}
