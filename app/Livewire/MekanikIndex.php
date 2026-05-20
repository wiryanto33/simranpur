<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Mekanik;

class MekanikIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterStatus = '';
    public bool $confirmingDeletion = false;
    public ?int $idToDelete = null;

    protected $queryString = [
        'search'       => ['except' => ''],
        'filterStatus' => ['except' => ''],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }

    #[\Livewire\Attributes\On('mekanikSaved')]
    public function handleSaved(string $message = ''): void
    {
        if ($message) session()->flash('message', $message);
        $this->resetPage();
    }

    public function confirmDelete(int $id): void
    {
        if (! auth()->user()->can('delete_mekanik')) abort(403);
        $this->confirmingDeletion = true;
        $this->idToDelete = $id;
    }

    public function delete(): void
    {
        if (! auth()->user()->can('delete_mekanik')) abort(403);
        Mekanik::findOrFail($this->idToDelete)->delete();
        $this->confirmingDeletion = false;
        $this->idToDelete = null;
        session()->flash('message', 'Data mekanik berhasil dihapus.');
    }

    public function render()
    {
        $query = Mekanik::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nama', 'like', '%' . $this->search . '%')
                  ->orWhere('nrp', 'like', '%' . $this->search . '%')
                  ->orWhere('pangkat', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        return view('livewire.mekanik-index', [
            'mekaniks' => $query->orderBy('nama')->paginate(10),
        ]);
    }
}
