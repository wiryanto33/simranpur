<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleIndex extends Component
{
    use WithPagination;

    public $search = '';

    public $confirmingDeletion = false;
    public $idToDelete = null;

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    #[\Livewire\Attributes\On('roleSaved')]
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

    public function deleteRole()
    {
        if(!auth()->user()->hasRole('Admin')) {
            abort(403);
        }

        $role = Role::findOrFail($this->idToDelete);
        
        // Proteksi role esensial
        if(in_array($role->name, ['Admin', 'Super Admin'])) {
            session()->flash('error', 'Role Admin tidak dapat dihapus.');
            $this->confirmingDeletion = false;
            return;
        }

        $role->delete();
        $this->confirmingDeletion = false;
        $this->idToDelete = null;
        
        session()->flash('message', 'Role berhasil dihapus beserta seluruh mapping permissionsenya.');
    }

    public function render()
    {
        $query = Role::with('permissions');

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        return view('livewire.role-index', [
            'roles' => $query->paginate(10),
        ]);
    }
}
