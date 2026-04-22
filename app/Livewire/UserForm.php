<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\DetailUser;
use App\Models\Kompi;
use App\Models\Kendaraan;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Notifications\SystemNotification;

class UserForm extends Component
{
    public $user_id;
    public $name;
    public $email;
    public $password;
    
    // Detail User
    public $pangkat;
    public $jabatan;
    public $kompi_id;
    public $kendaraan_id;
    
    // Roles Access (we'll just support single role assignment via select for simplicity)
    public $role = '';

    public $isEdit = false;
    public $showForm = false;

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($this->user_id)],
            'pangkat' => 'nullable|string|max:100',
            'jabatan' => 'nullable|string|max:100',
            'kompi_id' => 'nullable|exists:kompi,id',
            'kendaraan_id' => 'nullable|exists:kendaraan,id',
            'role' => 'required|exists:roles,name',
        ];

        if (!$this->isEdit) {
            $rules['password'] = 'required|string|min:8';
        } else {
            $rules['password'] = 'nullable|string|min:8'; // optional on edit
        }

        return $rules;
    }

    #[\Livewire\Attributes\On('createUser')]
    public function createUser()
    {
        $this->resetValidation();
        $this->reset(['user_id', 'name', 'email', 'password', 'pangkat', 'jabatan', 'kompi_id', 'kendaraan_id', 'role']);
        $this->isEdit = false;
        $this->showForm = true;
    }

    #[\Livewire\Attributes\On('editUser')]
    public function editUser($id)
    {
        $this->resetValidation();
        $user = User::with(['roles', 'detail'])->findOrFail($id);
        
        $this->user_id = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = ''; // clear password field
        
        if ($user->detail) {
            $this->pangkat = $user->detail->pangkat;
            $this->jabatan = $user->detail->jabatan;
            $this->kompi_id = $user->detail->kompi_id;
            $this->kendaraan_id = $user->detail->kendaraan_id;
        } else {
            $this->pangkat = '';
            $this->jabatan = '';
            $this->kompi_id = null;
            $this->kendaraan_id = null;
        }

        $this->role = $user->roles->first()->name ?? '';
        
        $this->isEdit = true;
        $this->showForm = true;
    }

    public function submit()
    {
        if(!auth()->user()->hasRole('Admin')) {
            abort(403);
        }

        $this->validate($this->rules());

        $userData = [
            'name' => $this->name,
            'email' => $this->email,
        ];

        if (!empty($this->password)) {
            $userData['password'] = Hash::make($this->password);
        }
        
        if ($this->role !== 'Operator') {
            $this->kendaraan_id = null;
        }

        if ($this->isEdit) {
            $user = User::findOrFail($this->user_id);
            $user->update($userData);
            $message = 'Data pengguna berhasil diperbarui.';
        } else {
            $user = User::create($userData);
            $message = 'Pengguna baru berhasil ditambahkan.';
        }

        // Handle Details
        DetailUser::updateOrCreate(
            ['user_id' => $user->id],
            [
                'pangkat' => $this->pangkat,
                'jabatan' => $this->jabatan,
                'kompi_id' => empty($this->kompi_id) ? null : $this->kompi_id,
                'kendaraan_id' => empty($this->kendaraan_id) ? null : $this->kendaraan_id,
            ]
        );

        // Assign Role (Sync allows single roles seamlessly)
        if ($this->role) {
            $user->syncRoles([$this->role]);
        }

        // Kirim notifikasi jika ditugaskan ke ranpur
        if ($this->role === 'Operator' && $this->kendaraan_id) {
             $kendaraan = Kendaraan::find($this->kendaraan_id);
             $user->notify(new SystemNotification(
                 'Penugasan Ranpur',
                 "Anda ditugaskan mengawaki " . ($kendaraan->nomor_ranpur ?? $kendaraan->nama) . ".",
                 '#',
                 'info'
             ));
        }

        $this->showForm = false;
        $this->dispatch('userSaved', message: $message);
    }

    public function render()
    {
        $roles = Role::orderBy('name')->get();
        $kompis = Kompi::orderBy('nama')->get();
        $kendaraans = Kendaraan::orderBy('nomor_ranpur')->get();

        return view('livewire.user-form', [
            'kompis' => $kompis,
            'kendaraans' => $kendaraans,
            'roles' => $roles
        ]);
    }
}
