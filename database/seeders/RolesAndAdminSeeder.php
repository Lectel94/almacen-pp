<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class RolesAndAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Crear roles
        $adminRole = Role::create(['name' => 'Admin']);
        $userRole1 = Role::create(['name' => 'Guest']);
        $userRole1 = Role::create(['name' => 'Usuario1']);
        $userRole2 = Role::create(['name' => 'Usuario2']);
        $userRole3 = Role::create(['name' => 'Usuario3']);

        // Crear usuario admin
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('123456789'),
        ]);

        // Asignar el rol de admin al usuario
        $admin->assignRole($adminRole);
    }
}
