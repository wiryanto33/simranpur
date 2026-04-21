<?php

namespace App\Livewire;

use Livewire\Component;
use Spatie\Activitylog\Models\Activity;
use App\Models\User;
use Livewire\WithPagination;

class LaporanAuditLog extends Component
{
    use WithPagination;

    public $search_user;
    public $search_modul;
    public $start_date;
    public $end_date;

    public function mount()
    {
        $this->start_date = now()->subDays(7)->format('Y-m-d');
        $this->end_date = now()->format('Y-m-d');
    }

    public function updating($name)
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Activity::with('causer')
            ->whereBetween('created_at', [$this->start_date . ' 00:00:00', $this->end_date . ' 23:59:59']);

        if ($this->search_user) {
            $query->where('causer_id', $this->search_user);
        }

        if ($this->search_modul) {
            $query->where('log_name', 'like', '%' . $this->search_modul . '%')
                  ->orWhere('subject_type', 'like', '%' . $this->search_modul . '%');
        }

        try {
            $users = \Illuminate\Support\Facades\Cache::remember('master_users_all', now()->addHour(), fn() => \App\Models\User::all());
            if (!($users instanceof \Illuminate\Support\Collection)) {
                \Illuminate\Support\Facades\Cache::forget('master_users_all');
                $users = \App\Models\User::all();
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Cache::forget('master_users_all');
            $users = \App\Models\User::all();
        }

        return view('livewire.laporan-audit-log', [
            'logs' => $query->latest()->paginate(20),
            'users' => $users,
        ]);
    }
}
