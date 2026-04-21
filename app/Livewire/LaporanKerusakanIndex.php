<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\LaporanKerusakan;
use App\Models\Kendaraan;

class LaporanKerusakanIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = '';
    public $filterPrioritas = '';

    public $confirmingDeletion = false;
    public $idToDelete = null;

    protected $queryString = [
        'search'          => ['except' => ''],
        'filterStatus'    => ['except' => ''],
        'filterPrioritas' => ['except' => ''],
    ];

    #[\Livewire\Attributes\On('laporanSaved')]
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
        if (!auth()->user()->can('delete_laporan_kerusakan')) abort(403);

        $laporan = LaporanKerusakan::findOrFail($this->idToDelete);

        // Kembalikan status kendaraan jika masih dalam proses akibat laporan ini
        if (in_array($laporan->status, ['Menunggu', 'Diverifikasi'])) {
            $laporan->kendaraan?->update(['status' => 'Siap Tempur']);
        }

        // Hapus foto dari storage
        if ($laporan->foto && is_array($laporan->foto)) {
            foreach ($laporan->foto as $path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
            }
        }

        $laporan->delete();
        $this->confirmingDeletion = false;
        $this->idToDelete = null;
        session()->flash('message', 'Laporan kerusakan berhasil dihapus.');
    }

    public function render()
    {
        $query = LaporanKerusakan::with(['kendaraan', 'pelapor']);
        $user = auth()->user();

        // PEMBATASAN OPERATOR: hanya tampilkan laporan ranpur sendiri
        if ($user->isOperator()) {
            if ($user->kendaraan_tugas_id) {
                $query->where('kendaraan_id', $user->kendaraan_tugas_id);
            } else {
                $query->whereRaw('1 = 0'); // Belum ditugaskan, list kosong
            }
        }

        if ($this->search) {
            $query->whereHas('kendaraan', function ($q) {
                $q->where('nama', 'like', '%' . $this->search . '%')
                  ->orWhere('nomor_ranpur', 'like', '%' . $this->search . '%')
                  ->orWhere('no_register', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        if ($this->filterPrioritas) {
            $query->where('tingkat_prioritas', $this->filterPrioritas);
        }

        return view('livewire.laporan-kerusakan-index', [
            'laporans' => $query->latest('tanggal')->paginate(10),
        ]);
    }
}
