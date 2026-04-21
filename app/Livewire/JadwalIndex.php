<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\JadwalPemeliharaan;
use App\Models\Kendaraan;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class JadwalIndex extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    public $filterJenis = '';
    public $filterStatus = '';
    public $filterMekanik = '';
    public $filterKendaraan = '';
    public $filterBulan = '';

    public $confirmingDeletion = false;
    public $idToDelete = null;

    public $showDetail = false;
    public $selectedJadwal = null;

    protected $queryString = [
        'filterJenis' => ['except' => ''],
        'filterStatus' => ['except' => ''],
        'filterMekanik' => ['except' => ''],
        'filterKendaraan' => ['except' => ''],
        'filterBulan' => ['except' => ''],
    ];

    public function updating()
    {
        $this->resetPage();
    }

    #[\Livewire\Attributes\On('jadwalSaved')]
    public function handleJadwalSaved($message = null)
    {
        if ($message) {
            session()->flash('message', $message);
        }
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        if(!auth()->user()->hasAnyRole(['Admin', 'KepMek'])) {
            abort(403, 'Akses ditolak.');
        }
        $this->confirmingDeletion = true;
        $this->idToDelete = $id;
    }

    public function deleteJadwal()
    {
        if(!auth()->user()->hasAnyRole(['Admin', 'KepMek'])) {
            abort(403);
        }
        
        JadwalPemeliharaan::findOrFail($this->idToDelete)->delete();
        $this->confirmingDeletion = false;
        $this->idToDelete = null;
        
        session()->flash('message', 'Jadwal pemeliharaan berhasil dihapus/dibatalkan.');
    }

    #[\Livewire\Attributes\On('refreshJadwal')]
    public function refreshList()
    {
        $this->resetPage();
    }

    public function updateStatus($id, $status)
    {
        $jadwal = JadwalPemeliharaan::findOrFail($id);
        
        // Cek permission
        $user = auth()->user();
        $canUpdate = false;
        
        if($user->hasRole('Admin') || $user->hasRole('KepMek')) {
            $canUpdate = true;
        } elseif ($user->hasRole('Mekanik') && $jadwal->mekanik_id == $user->id) {
            // Mekanik hanya boleh update Terjadwal -> Sedang Dikerjakan -> Selesai
            if(in_array($status, ['Sedang Dikerjakan', 'Selesai'])) {
                $canUpdate = true;
            }
        }
        
        if(!$canUpdate) abort(403);
        
        $jadwal->status = $status;
        $jadwal->save();

        // Update status kendaraan jika selesai
        if($status == 'Selesai') {
            if($jadwal->kendaraan) {
                // Return to Siap Tempur or Standby
                $jadwal->kendaraan->update(['status' => 'Siap Tempur']);
            }
        }

        session()->flash('message', 'Status jadwal diperbarui.');
        $this->dispatch('jadwalSaved'); // trigger kalender refresh too
    }

    public function viewDetails($id)
    {
        $this->selectedJadwal = JadwalPemeliharaan::with(['kendaraan', 'mekanik'])->findOrFail($id);
        $this->showDetail = true;
    }

    public function exportCsv()
    {
        $query = $this->buildQuery();
        $data = $query->with('mekanik')->get();
        
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=jadwal_pemeliharaan.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];
        
        $callback = function() use($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Tanggal', 'No. Ranpur', 'Nama Kendaraan', 'Jenis Pemeliharaan', 'Mekanik', 'Status']);

            foreach ($data as $row) {
                fputcsv($file, [
                    $row->tanggal->format('Y-m-d'),
                    $row->kendaraan->nomor_ranpur ?? '-',
                    $row->kendaraan->nama ?? '-',
                    $row->jenis_pemeliharaan,
                    $row->mekanik->pluck('name')->implode(', ') ?: '-',
                    $row->status,
                ]);
            }
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    private function buildQuery()
    {
        $query = JadwalPemeliharaan::with(['kendaraan', 'mekanik', 'mekanik.detail']);

        // Filter for specific mechanic if user is a Mechanic
        if (auth()->user()->hasRole('Mekanik')) {
            $query->whereHas('mekanik', function($q) {
                $q->where('users.id', auth()->id());
            });
        }

        // filter
        if ($this->filterJenis) $query->where('jenis_pemeliharaan', $this->filterJenis);
        if ($this->filterStatus) $query->where('status', $this->filterStatus);
        if ($this->filterMekanik) $query->whereHas('mekanik', fn($q) => $q->where('users.id', $this->filterMekanik));
        if ($this->filterKendaraan) $query->where('kendaraan_id', $this->filterKendaraan);
        
        if ($this->filterBulan) {
            $parts = explode('-', $this->filterBulan);
            if(count($parts) == 2) {
                $query->whereYear('tanggal', $parts[0])
                      ->whereMonth('tanggal', $parts[1]);
            }
        }

        return $query->latest('tanggal');
    }

    public function render()
    {
        // Hardening Cache Retrieval
        try {
            $mekaniks = \Illuminate\Support\Facades\Cache::remember('master_mekaniks', now()->addHour(), fn() => User::role('Mekanik')->get());
            if (!($mekaniks instanceof \Illuminate\Support\Collection)) {
                \Illuminate\Support\Facades\Cache::forget('master_mekaniks');
                $mekaniks = User::role('Mekanik')->get();
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Cache::forget('master_mekaniks');
            $mekaniks = User::role('Mekanik')->get();
        }

        try {
            $kendaraans = \Illuminate\Support\Facades\Cache::remember('master_kendaraan', now()->addDay(), fn() => Kendaraan::all());
            if (!($kendaraans instanceof \Illuminate\Support\Collection)) {
                \Illuminate\Support\Facades\Cache::forget('master_kendaraan');
                $kendaraans = Kendaraan::all();
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Cache::forget('master_kendaraan');
            $kendaraans = Kendaraan::all();
        }

        return view('livewire.jadwal-index', [
            'jadwalList' => $this->buildQuery()->paginate(10),
            'mekaniks'   => $mekaniks,
            'kendaraans' => $kendaraans,
        ]);
}
