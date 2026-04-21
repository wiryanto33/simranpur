<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Kompi;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class KompiIndex extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    public $search = '';
    
    public $confirmingDeletion = false;
    public $idToDelete = null;

    protected $queryString = ['search' => ['except' => '']];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    #[\Livewire\Attributes\On('kompiSaved')]
    public function handleKompiSaved($message = null)
    {
        if ($message) {
            session()->flash('message', $message);
        }
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        if(!auth()->user()->hasAnyRole(['Admin'])) {
            abort(403, 'Akses ditolak.');
        }
        $this->confirmingDeletion = true;
        $this->idToDelete = $id;
    }

    public function deleteKompi()
    {
        if(!auth()->user()->hasAnyRole(['Admin'])) {
            abort(403);
        }
        
        Kompi::findOrFail($this->idToDelete)->delete();
        $this->confirmingDeletion = false;
        $this->idToDelete = null;
        
        session()->flash('message', 'Kompi berhasil dihapus.');
    }

    public function render()
    {
        $query = Kompi::query();

        if ($this->search) {
            $query->where('nama', 'like', '%' . $this->search . '%')
                  ->orWhere('kode', 'like', '%' . $this->search . '%');
        }

        return view('livewire.kompi-index', [
            'kompiList' => $query->latest()->paginate(10)
        ]);
    }
}
