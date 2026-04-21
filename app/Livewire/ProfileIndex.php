<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;

class ProfileIndex extends Component
{
    use WithFileUploads;

    public $name;
    public $email;
    public $avatar;
    public $new_avatar;

    public $current_password;
    public $password;
    public $password_confirmation;

    public function mount()
    {
        $user = auth()->user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->avatar = $user->detail->avatar ?? null;
    }

    public function updateProfile()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . auth()->id(),
            'new_avatar' => 'nullable|image|max:1024',
        ]);

        $user = auth()->user();
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        if ($this->new_avatar) {
            if ($user->detail && $user->detail->avatar) {
                Storage::disk('public')->delete($user->detail->avatar);
            }
            
            $path = $this->new_avatar->store('avatars', 'public');
            
            \App\Models\DetailUser::updateOrCreate(
                ['user_id' => $user->id],
                ['avatar' => $path]
            );
            
            $this->avatar = $path;
            $this->new_avatar = null;
        }

        session()->flash('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|min:8|confirmed',
        ]);

        auth()->user()->update([
            'password' => Hash::make($this->password),
        ]);

        $this->reset(['current_password', 'password', 'password_confirmation']);
        session()->flash('success_password', 'Password berhasil diubah.');
    }

    public function render()
    {
        $activities = Activity::where('causer_id', auth()->id())
            ->where('log_name', 'auth')
            ->latest()
            ->take(5)
            ->get();

        return view('livewire.profile-index', [
            'activities' => $activities
        ])->layout('layouts.app');
    }
}
