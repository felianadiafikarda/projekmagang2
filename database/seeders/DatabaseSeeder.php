<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // === Tambahkan role default (jika belum ada) ===
        $defaultRoles = [
            ['name' => 'author', 'display_name' => 'Author', 'level' => 1],
            ['name' => 'reviewer', 'display_name' => 'Reviewer', 'level' => 2],
            ['name' => 'editor', 'display_name' => 'Editor', 'level' => 3],
            ['name' => 'conference_manager', 'display_name' => 'Conference Manager', 'level' => 4],
        ];

        foreach ($defaultRoles as $role) {
            Role::firstOrCreate(['name' => $role['name']], $role);
        }

        // === Buat akun Super Admin ===
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'username' => 'superadmin',
                'password' => bcrypt('password'),
            ]
        );

        // === Pastikan role conference_manager terpasang ===
        $conferenceManagerRole = Role::where('name', 'conference_manager')->first();
        if ($conferenceManagerRole && !$admin->roles()->where('role_id', $conferenceManagerRole->id)->exists()) {
            $admin->roles()->attach($conferenceManagerRole);
        }

        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
        ]);
    }
}
