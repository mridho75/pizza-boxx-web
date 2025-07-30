<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AssignRoleToExistingUsersSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan role sudah ada dengan guard yang sesuai
        $roles = [
            ['name' => 'admin', 'guard_name' => 'employee'],
            ['name' => 'employee', 'guard_name' => 'employee'],
            ['name' => 'customer', 'guard_name' => 'web'],
        ];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role['name'], 'guard_name' => $role['guard_name']]);
        }

        // Assign role ke user sesuai kolom 'role' di tabel users
        $users = User::all();
        foreach ($users as $user) {
            if ($user->role === 'admin' || $user->role === 'employee') {
                $user->assignRole($user->role, 'employee');
            } elseif ($user->role === 'customer') {
                $user->assignRole($user->role); // default guard web
            }
        }
    }
}
