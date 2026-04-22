<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Kendaraan;
use App\Models\Kompi;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class KendaraanIndex extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    public $search = '';
    public $filterStatus = '';
    public $filterKompi = '';
    
    public $confirmingDeletion = false;
    public $idToDelete = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterStatus' => ['except' => ''],
        'filterKompi' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function updatingFilterKompi()
    {
        $this->resetPage();
    }

    #[\Livewire\Attributes\On('kendaraanSaved')]
    public function handleKendaraanSaved($message = null)
    {
        if ($message) {
            session()->flash('message', $message);
        }
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        if(!auth()->user()->hasAnyRole(['Admin', 'KepMek'])) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus data kendaraan.');
        }
        $this->confirmingDeletion = true;
        $this->idToDelete = $id;
    }

    public function deleteKendaraan()
    {
        if(!auth()->user()->hasAnyRole(['Admin', 'KepMek'])) {
            abort(403);
        }
        
        Kendaraan::findOrFail($this->idToDelete)->delete();
        $this->confirmingDeletion = false;
        $this->idToDelete = null;
        
        session()->flash('message', 'Kendaraan berhasil dihapus.');
    }

    public function render()
    {
        $query = Kendaraan::with('kompi');

        if ($this->search) {
            $query->where(function($q) {
                $q->where('nama', 'like', '%' . $this->search . '%')
                  ->orWhere('nomor_ranpur', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        if ($this->filterKompi) {
            $query->where('kompi_id', $this->filterKompi);
        }

        $kendaraanList = $query->latest()->paginate(15);
        $kompiList = Kompi::orderBy('nama')->get();

        return view('livewire.kendaraan-index', [
            'kendaraanList' => $kendaraanList,
            'kompiList' => $kompiList,
        ]);
    }
}
