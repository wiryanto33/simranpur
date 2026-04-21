<?php

namespace App\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RoleForm extends Component
{
    public $role_id;
    public $name;
    
    // Array to bind permissions checkboxes
    public $selectedPermissions = [];

    public $isEdit = false;
    public $showForm = false;

    // We will group permissions by model resource for better UI
    public $permissionsGrouped = [];

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:roles,name,' . $this->role_id,
            'selectedPermissions' => 'nullable|array',
            'selectedPermissions.*' => 'exists:permissions,name'
        ];
    }

    public function mount()
    {
        $this->loadGroupedPermissions();
    }

    private function loadGroupedPermissions()
    {
        $permissions = Permission::all();
        $grouped = [];

        foreach($permissions as $p) {
            // Permission format like "create_jadwal_pemeliharaan"
            // Let's explode by '_'
            $parts = explode('_', $p->name, 2);
            $action = $parts[0]; // create, edit, delete, view
            $resource = isset($parts[1]) ? $parts[1] : 'other'; // jadwal_pemeliharaan, kendaraan

            // format Title 
            $resTitle = ucwords(str_replace('_', ' ', $resource));
            
            if(!isset($grouped[$resTitle])) {
                $grouped[$resTitle] = [];
            }
            $grouped[$resTitle][] = [
                'name' => $p->name,
                'action_label' => ucfirst($action)
            ];
        }

        $this->permissionsGrouped = $grouped;
    }

    #[\Livewire\Attributes\On('createRole')]
    public function createRole()
    {
        if(!auth()->user()->hasRole('Admin')) {
            abort(403);
        }
        $this->resetValidation();
        $this->reset(['role_id', 'name', 'selectedPermissions']);
        
        $this->isEdit = false;
        $this->showForm = true;
    }

    #[\Livewire\Attributes\On('editRole')]
    public function editRole($id)
    {
        if(!auth()->user()->hasRole('Admin')) {
            abort(403);
        }
        $this->resetValidation();
        $role = Role::with('permissions')->findOrFail($id);
        
        $this->role_id = $role->id;
        $this->name = $role->name;
        
        // Fill checkboxes with existing permissions
        $this->selectedPermissions = $role->permissions->pluck('name')->toArray();
        
        $this->isEdit = true;
        $this->showForm = true;
    }

    public function selectAll()
    {
        $this->selectedPermissions = Permission::pluck('name')->toArray();
    }

    public function deselectAll()
    {
        $this->selectedPermissions = [];
    }

    public function submit()
    {
        if(!auth()->user()->hasRole('Admin')) {
            abort(403);
        }

        $this->validate();

        if ($this->isEdit) {
            $role = Role::findOrFail($this->role_id);
            $role->update(['name' => $this->name]);
            $message = 'Role berhasil diperbarui.';
        } else {
            $role = Role::create(['name' => $this->name]);
            $message = 'Role baru berhasil ditambahkan.';
        }

        // Sync Spatie Permissions
        $role->syncPermissions($this->selectedPermissions);

        // Flush Spatie permission cache immediately
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->showForm = false;
        $this->dispatch('roleSaved', message: $message);
    }

    public function render()
    {
        return view('livewire.role-form');
    }
}
