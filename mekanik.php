<?php
$role = Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Mekanik']);
$user = App\Models\User::firstOrCreate(
    ['email' => 'mekanik@test.com'], 
    ['name' => 'Budi Mekanik', 'password' => bcrypt('password')]
);
$user->assignRole('Mekanik');
echo "Sukses membuat role dan user mekanik.\n";
