<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\JadwalPemeliharaan;

class JadwalKalender extends Component
{
    // Whenever jadwal Saved, we want to refresh this kalender
    #[\Livewire\Attributes\On('jadwalSaved')]
    public function render()
    {
        $query = JadwalPemeliharaan::with(['kendaraan', 'mekanik']);
        
        // Filter for specific mechanic if user is a Mechanic
        if (auth()->user()->hasRole('Mekanik')) {
            $query->whereHas('mekanik', function($q) {
                $q->where('users.id', auth()->id());
            });
        }
        
        $jadwal = $query->get();
        
        $events = $jadwal->map(function ($j) {
            // ... (color logic remains the same)
            $color = match($j->jenis_pemeliharaan) {
                'Harian' => '#10B981', 
                'Mingguan' => '#3B82F6', 
                'Bulanan' => '#8B5CF6', 
                'Triwulan' => '#F59E0B', 
                'Tahunan' => '#EF4444', 
                'Insidentil' => '#6B7280', 
                default => '#374151'
            };
            
            $endDate = $j->tanggal->copy()->addDays($j->estimasi_hari);

            return [
                'id' => $j->id,
                'title' => ($j->kendaraan->nomor_ranpur ?? 'Ranpur') . ' - ' . $j->jenis_pemeliharaan,
                'start' => $j->tanggal->format('Y-m-d'),
                'end' => $endDate->format('Y-m-d'),
                'color' => $color,
                'allDay' => true,
                'extendedProps' => [
                    'jenis' => $j->jenis_pemeliharaan,
                    'status' => $j->status,
                    'kendaraan' => $j->kendaraan->nama ?? '-',
                    'mekanik' => $j->mekanik->pluck('name')->implode(', ') ?: '-',
                    'estimasi' => $j->estimasi_hari,
                    'checklistCount' => is_array($j->checklist) ? count($j->checklist) : 0
                ]
            ];
        });

        return view('livewire.jadwal-kalender', [
            'eventsJson' => $events->toJson()
        ]);
    }
}
