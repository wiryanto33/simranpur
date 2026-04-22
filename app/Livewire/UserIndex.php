<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $filterRole = '';

    public $confirmingDeletion = false;
    public $idToDelete = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterRole' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterRole()
    {
        $this->resetPage();
    }

    #[\Livewire\Attributes\On('userSaved')]
    public function handleRefresh($message = null)
    {
        if ($message) {
            session()->flash('message', $message);
        }
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $this->confirmingDeletion = true;
        $this->idToDelete = $id;
    }

    public function deleteUser()
    {
        if(!auth()->user()->hasRole('Admin')) {
            abort(403);
        }

        if ($this->idToDelete == auth()->id()) {
            session()->flash('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
            $this->confirmingDeletion = false;
            return;
        }

        User::findOrFail($this->idToDelete)->delete();
        $this->confirmingDeletion = false;
        $this->idToDelete = null;
        
        session()->flash('message', 'Pengguna berhasil dihapus.');
    }

    public function render()
    {
        $query = User::with(['roles', 'detail.kompi']);

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
        }

        if ($this->filterRole) {
            $query->whereHas('roles', function($q) {
                $q->where('name', $this->filterRole);
            });
        }

        $roles = \Spatie\Permission\Models\Role::orderBy('name')->get();

        return view('livewire.user-index', [
            'users' => $query->latest()->paginate(10),
            'roles' => $roles,
        ]);
    }
}
