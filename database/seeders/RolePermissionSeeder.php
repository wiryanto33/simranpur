<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Roles
        $roles = [
            'Admin',
            'Komandan',
            'KepMek',
            'Logistik',
            'Operator',
            'Mekanik',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // =====================================================================
        // MODUL CRUD PERMISSIONS
        // =====================================================================
        $modules = [
            'kendaraan',
            'jadwal_pemeliharaan',
            'laporan_kerusakan',
            'laporan_perbaikan',
            'suku_cadang',
            'transaksi_suku_cadang',
            'permintaan_suku_cadang',
            'user',
        ];

        foreach ($modules as $module) {
            Permission::firstOrCreate(['name' => "view_$module"]);
            Permission::firstOrCreate(['name' => "create_$module"]);
            Permission::firstOrCreate(['name' => "edit_$module"]);
            Permission::firstOrCreate(['name' => "delete_$module"]);
        }

        Permission::firstOrCreate(['name' => 'approve_permintaan_suku_cadang']);

        // =====================================================================
        // LAPORAN & REKAP PERMISSIONS (granular per sub-modul)
        // =====================================================================
        $laporanPermissions = [
            'view_laporan_rekap_kesiapan',      // Laporan kesiapan armada ranpur
            'view_laporan_rekap_pemeliharaan',   // Laporan rekap jadwal pemeliharaan
            'view_laporan_rekap_kerusakan',      // Laporan rekap kerusakan & perbaikan
            'view_laporan_rekap_suku_cadang',    // Laporan rekap penggunaan suku cadang
            'view_laporan_audit_log',            // Audit log sistem (admin only)
            'export_laporan',                    // Hak ekspor PDF/Excel
        ];

        foreach ($laporanPermissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // =====================================================================
        // ASSIGN PERMISSIONS TO ROLES
        // =====================================================================

        // ADMIN — akses penuh
        $adminRole = Role::findByName('Admin');
        $adminRole->syncPermissions(Permission::all());

        // KOMANDAN — lihat semua laporan (read only, tidak bisa kelola data)
        $komandanRole = Role::findByName('Komandan');
        $komandanRole->syncPermissions([
            'view_kendaraan',
            'view_jadwal_pemeliharaan',
            'view_laporan_kerusakan',
            'view_laporan_perbaikan',
            'view_suku_cadang',
            // Laporan rekap (semua kecuali audit log)
            'view_laporan_rekap_kesiapan',
            'view_laporan_rekap_pemeliharaan',
            'view_laporan_rekap_kerusakan',
            'view_laporan_rekap_suku_cadang',
            'export_laporan',
        ]);

        // KEPMEK — kelola mekanik & laporan teknis
        $kepmekRole = Role::findByName('KepMek');
        $kepmekRole->syncPermissions([
            'view_kendaraan',
            'view_jadwal_pemeliharaan',
            'view_laporan_kerusakan',
            'create_laporan_kerusakan',
            'edit_laporan_kerusakan',
            'view_laporan_perbaikan',
            'create_laporan_perbaikan',
            'edit_laporan_perbaikan',
            'view_suku_cadang',
            'view_permintaan_suku_cadang',
            'create_permintaan_suku_cadang',
            // Laporan rekap teknis
            'view_laporan_rekap_kesiapan',
            'view_laporan_rekap_pemeliharaan',
            'view_laporan_rekap_kerusakan',
            'view_laporan_rekap_suku_cadang',
            'export_laporan',
        ]);

        // LOGISTIK — kelola suku cadang & laporan logistik
        $logistikRole = Role::findByName('Logistik');
        $logistikRole->syncPermissions([
            'view_suku_cadang',
            'create_suku_cadang',
            'edit_suku_cadang',
            'delete_suku_cadang',
            'view_permintaan_suku_cadang',
            'approve_permintaan_suku_cadang',
            'view_transaksi_suku_cadang',
            // Laporan rekap logistik
            'view_laporan_rekap_kesiapan',
            'view_laporan_rekap_suku_cadang',
            'export_laporan',
        ]);

        // OPERATOR — input laporan kerusakan, tidak bisa lihat rekap
        $operatorRole = Role::findByName('Operator');
        $operatorRole->syncPermissions([
            'view_kendaraan',
            'view_laporan_kerusakan',
            'create_laporan_kerusakan',
        ]);

        // MEKANIK — lihat jadwal & laporan teknis yang ditugaskan
        $mekanikRole = Role::findByName('Mekanik');
        $mekanikRole->syncPermissions([
            'view_kendaraan',
            'view_jadwal_pemeliharaan',
            'view_laporan_kerusakan',
            'view_laporan_perbaikan',
            'create_laporan_perbaikan',
            'view_suku_cadang',
            'view_permintaan_suku_cadang',
            'create_permintaan_suku_cadang',
            // Laporan rekap terbatas untuk mekanik
            'view_laporan_rekap_pemeliharaan',
            'view_laporan_rekap_kerusakan',
        ]);

        // =====================================================================
        // CREATE DEFAULT USERS
        // =====================================================================
        $users = [
            ['name' => 'Super Admin',     'email' => 'admin@simranpur.com',    'role' => 'Admin'],
            ['name' => 'Komandan Marinir','email' => 'komandan@simranpur.com', 'role' => 'Komandan'],
            ['name' => 'Kepala Mekanik',  'email' => 'kepmek@simranpur.com',   'role' => 'KepMek'],
            ['name' => 'Kepala Logistik', 'email' => 'logistik@simranpur.com', 'role' => 'Logistik'],
            ['name' => 'Operator Ranpur', 'email' => 'operator@simranpur.com', 'role' => 'Operator'],
        ];

        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name'              => $userData['name'],
                    'password'          => Hash::make('password'),
                    'email_verified_at' => now(),
                    'remember_token'    => Str::random(10),
                ]
            );
            $user->assignRole($userData['role']);
        }
    }
}
