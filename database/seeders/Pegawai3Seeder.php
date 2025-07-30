<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class Pegawai3Seeder extends Seeder
{
    public function run(): void
    {
        // Pastikan role employee sudah ada
        $role = Role::firstOrCreate([
            'name' => 'employee',
            'guard_name' => 'employee',
        ]);

        // Buat user pegawai3 jika belum ada
        $user = User::firstOrCreate(
            ['email' => 'pegawai3@gmail.com'],
            [
                'name' => 'Pegawai 3',
                'password' => Hash::make('pegawai3'),
                'role' => 'employee',
            ]
        );

        // Assign role employee via Spatie (pastikan guard employee)
        $user->assignRole('employee', 'employee');
    }
}
