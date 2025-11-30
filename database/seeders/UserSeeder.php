<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $kasirRole = Role::where('name', 'kasir')->first();
        $gudangRole = Role::where('name', 'gudang')->first();
        $managerRole = Role::where('name', 'manager')->first();

        // Administrator
        User::create([
            'name' => 'Administrator',
            'username' => 'Adminonly',
            'password' => Hash::make('khususadmin'),
            'role_id' => $adminRole->id,
        ]);

        // Kasir - 3 akun
        User::create([
            'name' => 'Ferdi',
            'username' => 'Ferdi',
            'password' => Hash::make('kasir123'),
            'role_id' => $kasirRole->id,
        ]);

        User::create([
            'name' => 'Dudung',
            'username' => 'Dudung',
            'password' => Hash::make('kasir123'),
            'role_id' => $kasirRole->id,
        ]);

        User::create([
            'name' => 'Farrel',
            'username' => 'Farrel',
            'password' => Hash::make('kasir123'),
            'role_id' => $kasirRole->id,
        ]);

        // Gudang
        User::create([
            'name' => 'Staff Gudang',
            'username' => 'Gudang1',
            'password' => Hash::make('gudang123'),
            'role_id' => $gudangRole->id,
        ]);

        // Manager
        User::create([
            'name' => 'Manager',
            'username' => 'Manager1',
            'password' => Hash::make('manager123'),
            'role_id' => $managerRole->id,
        ]);
    }
}

