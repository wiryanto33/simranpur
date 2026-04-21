<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SyncAdminPermissionsSeeder extends Seeder
{
    public function run()
    {
        $adminRole = Role::findByName('Admin');
        $adminRole->syncPermissions(Permission::all());
        echo "Admin role synced with " . Permission::count() . " permissions.\n";
    }
}
