<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\JadwalPemeliharaan;
use App\Models\User;
use App\Notifications\SystemNotification;
use Illuminate\Support\Facades\Notification;

class CheckJadwalPemeliharaan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-jadwal-pemeliharaan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cek jadwal H-1 dan yang melewati batas waktu untuk dikirim notifikasi';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // 1. Jadwal H-1 (Besok)
        $besok = now()->addDay()->format('Y-m-d');
        $jadwalBesok = JadwalPemeliharaan::with('mekanik', 'kendaraan')
            ->whereDate('tanggal', $besok)
            ->where('status', 'Terjadwal')
            ->get();

        foreach ($jadwalBesok as $j) {
            foreach ($j->mekanik as $m) {
                $m->notify(new SystemNotification(
                    'Pengingat: Jadwal Pemeliharaan Besok',
                    'Anda memiliki jadwal pemeliharaan untuk kendaraan ' . ($j->kendaraan->nomor_ranpur ?? $j->kendaraan->nama) . ' besok.',
                    route('jadwal.index'),
                    'info'
                ));
            }
        }

        // 2. Jadwal Melewati Tanggal (Overdue)
        $hariIni = now()->format('Y-m-d');
        $jadwalOverdue = JadwalPemeliharaan::with('kendaraan')
            ->whereDate('tanggal', '<', $hariIni)
            ->whereNotIn('status', ['Selesai', 'Dibatalkan'])
            ->get();

        if ($jadwalOverdue->count() > 0) {
            $kepMeks = User::role('KepMek')->get();
            $komandans = User::role('Komandan')->get();
            $notifUsers = $kepMeks->concat($komandans);

            foreach ($jadwalOverdue as $j) {
                Notification::send($notifUsers, new SystemNotification(
                    'Peringatan: Jadwal Melewati Batas',
                    'Jadwal pemeliharaan ' . ($j->kendaraan->nomor_ranpur ?? $j->kendaraan->nama) . ' (' . $j->tanggal->format('d M Y') . ') belum selesai.',
                    route('jadwal.index'),
                    'danger'
                ));
            }
        }

        $this->info('Pengecekan jadwal selesai.');
    }
}
