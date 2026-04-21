<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\SukuCadang;

class SukuCadangIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStok = ''; // 'menipis' or ''

    protected $queryString = [
        'search'     => ['except' => ''],
        'filterStok' => ['except' => ''],
    ];

    public $confirmingDeletion = false;
    public $idToDelete = null;

    #[\Livewire\Attributes\On('sukuCadangSaved')]
    public function handleRefresh($message = null)
    {
        if ($message) session()->flash('message', $message);
        $this->resetPage();
    }

    public function updatingSearch() { $this->resetPage(); }

    public function confirmDelete($id)
    {
        $this->confirmingDeletion = true;
        $this->idToDelete = $id;
    }

    public function delete()
    {
        $sc = SukuCadang::findOrFail($this->idToDelete);

        // Hapus foto jika ada
        if ($sc->foto) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($sc->foto);
        }

        $sc->delete();
        $this->confirmingDeletion = false;
        $this->idToDelete = null;
        session()->flash('message', 'Data suku cadang berhasil dihapus.');
    }

    public function render()
    {
        $query = SukuCadang::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nama', 'like', '%' . $this->search . '%')
                  ->orWhere('kode', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterStok === 'menipis') {
            $query->whereColumn('stok', '<=', 'stok_minimum');
        }

        $totalMenipis = \Illuminate\Support\Facades\Cache::remember('total_stok_menipis', now()->addMinutes(15), function() {
            return SukuCadang::whereColumn('stok', '<=', 'stok_minimum')->count();
        });

        return view('livewire.suku-cadang-index', [
            'items' => $query->orderBy('nama')->paginate(12),
            'totalStokMenipis' => $totalMenipis,
        ]);
    }
}
