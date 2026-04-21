<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Kendaraan;
use App\Models\Kompi;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class KendaraanForm extends Component
{
    use WithFileUploads;
    use AuthorizesRequests;

    public $kendaraan_id;
    public $nomor_ranpur;
    public $nama;
    public $jenis;
    public $tahun;
    public $kompi_id;
    public $status = 'Siap Tempur';
    public $keterangan;
    
    public $foto;
    public $fotoPrev;
    
    public $isEdit = false;
    public $showForm = false;

    protected function rules()
    {
        return [
            'nomor_ranpur' => ['required', 'string', 'max:255', \Illuminate\Validation\Rule::unique('kendaraan')->ignore($this->kendaraan_id)],
            'nama' => 'required|string|max:255',
            'jenis' => 'required|string|max:255',
            'tahun' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'kompi_id' => 'required|exists:kompi,id',
            'status' => 'required|in:Siap Tempur,Standby,Perbaikan,Tidak Layak',
            'foto' => 'nullable|image|max:2048',
        ];
    }

    protected $messages = [
        'nomor_ranpur.required' => 'Nomor Ranpur wajib diisi.',
        'nomor_ranpur.unique' => 'Nomor Ranpur sudah terdaftar.',
        'nama.required' => 'Nama kendaraan wajib diisi.',
        'jenis.required' => 'Jenis ranpur wajib diisi.',
        'tahun.required' => 'Tahun wajib diisi.',
        'kompi_id.required' => 'Kompi wajib dipilih.',
        'status.required' => 'Status wajib dipilih.',
        'foto.image' => 'File harus berupa gambar.',
        'foto.max' => 'Ukuran foto maksimal 2MB.',
    ];

    #[\Livewire\Attributes\On('createKendaraan')]
    public function createKendaraan()
    {
        if(!auth()->user()->hasAnyRole(['Admin', 'KepMek'])) {
            abort(403, 'Akses ditolak.');
        }
        $this->resetValidation();
        $this->reset(['kendaraan_id', 'nomor_ranpur', 'nama', 'jenis', 'tahun', 'kompi_id', 'keterangan', 'foto', 'fotoPrev']);
        $this->status = 'Siap Tempur';
        $this->isEdit = false;
        $this->showForm = true;
    }

    #[\Livewire\Attributes\On('editKendaraan')]
    public function editKendaraan($id)
    {
        if(!auth()->user()->hasAnyRole(['Admin', 'KepMek'])) {
            abort(403, 'Akses ditolak.');
        }
        $this->resetValidation();
        $kendaraan = Kendaraan::findOrFail($id);
        
        $this->kendaraan_id = $kendaraan->id;
        $this->nomor_ranpur = $kendaraan->nomor_ranpur;
        $this->nama = $kendaraan->nama;
        $this->jenis = $kendaraan->jenis;
        $this->tahun = $kendaraan->tahun;
        $this->kompi_id = $kendaraan->kompi_id;
        $this->status = $kendaraan->status;
        $this->fotoPrev = $kendaraan->foto;
        
        $this->isEdit = true;
        $this->showForm = true;
    }

    public function submit()
    {
        $this->validate($this->rules());

        $data = [
            'nomor_ranpur' => $this->nomor_ranpur,
            'nama' => $this->nama,
            'jenis' => $this->jenis,
            'tahun' => $this->tahun,
            'kompi_id' => $this->kompi_id,
            'status' => $this->status,
        ];

        if ($this->foto) {
            $path = $this->foto->store('kendaraan', 'public');
            $data['foto'] = $path;
            
            if ($this->isEdit && $this->fotoPrev) {
                Storage::disk('public')->delete($this->fotoPrev);
            }
        }

        if ($this->isEdit) {
            Kendaraan::where('id', $this->kendaraan_id)->update($data);
            $message = 'Data kendaraan berhasil diperbarui.';
        } else {
            Kendaraan::create($data);
            $message = 'Kendaraan baru berhasil ditambahkan.';
        }

        $this->showForm = false;
        $this->dispatch('kendaraanSaved', message: $message);
    }

    public function render()
    {
        try {
            $kompiList = \Illuminate\Support\Facades\Cache::remember('master_kompi', now()->addDay(), fn() => Kompi::all());
            if (!($kompiList instanceof \Illuminate\Support\Collection)) {
                \Illuminate\Support\Facades\Cache::forget('master_kompi');
                $kompiList = Kompi::all();
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Cache::forget('master_kompi');
            $kompiList = Kompi::all();
        }

        return view('livewire.kendaraan-form', [
            'kompiList' => $kompiList
        ]);
}
