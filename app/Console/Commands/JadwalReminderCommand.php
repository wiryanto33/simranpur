<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\JadwalPemeliharaan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
// Jika menggunakan sistem notifikasi database bawaan, buat class Notification: JadwalReminderNotification
// Karena di fase ini belum ada request untuk membuat Notification Class secara detail, 
// kita akan asumsikan kita menyimpan row sederhana atau sekadar log.
use Illuminate\Support\Facades\Log;

class JadwalReminderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jadwal:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim reminder H-1 untuk teknisi dan notifikasi KepMek jika jadwal melewati tenggat.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();

        $this->info("Menyiapkan reminder untuk jadwal pemeliharaan...");

        // 1. Kirim reminder H-1 ke mekanik
        $jadwalBesok = JadwalPemeliharaan::with('mekanik', 'kendaraan')
            ->whereDate('tanggal', $tomorrow)
            ->whereIn('status', ['Terjadwal', 'Ditunda'])
            ->get();

        foreach($jadwalBesok as $j) {
            if($j->mekanik) {
                // Contoh: Notification::send($j->mekanik, new ReminderNotification($j));
                Log::info("Reminder: Jadwal pemeliharaan ranpur {$j->kendaraan->nomor_ranpur} besok. Assigned to {$j->mekanik->name}");
            }
        }
        $this->info("Dikirim " . count($jadwalBesok) . " reminder H-1 ke mekanik.");

        // 2. Kirim notifikasi ke KepMek jika melewati jadwal tapi belum selesai
        $jadwalLewat = JadwalPemeliharaan::with('kendaraan')
            ->whereDate('tanggal', '<', $today)
            ->whereNotIn('status', ['Selesai', 'Dibatalkan'])
            ->get();

        if (count($jadwalLewat) > 0) {
            $kepMeks = User::role('KepMek')->get();
            foreach($jadwalLewat as $j) {
                // Contoh: Notification::send($kepMeks, new OverdueNotification($j));
                Log::warning("Overdue: Jadwal {$j->kendaraan->nomor_ranpur} belum selesai (Status: {$j->status}). Tgl: {$j->tanggal->format('Y-m-d')}");
            }
        }
        $this->info("Ditemukan " . count($jadwalLewat) . " jadwal overdue.");

        $this->info("Reminder berhasil dieksekusi.");
    }
}
