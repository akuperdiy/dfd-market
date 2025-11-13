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

        User::create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role_id' => $adminRole->id,
        ]);

        User::create([
            'name' => 'Kasir 1',
            'email' => 'kasir@example.com',
            'password' => Hash::make('password123'),
            'role_id' => $kasirRole->id,
        ]);

        User::create([
            'name' => 'Staff Gudang',
            'email' => 'gudang@example.com',
            'password' => Hash::make('password123'),
            'role_id' => $gudangRole->id,
        ]);
    }
}

