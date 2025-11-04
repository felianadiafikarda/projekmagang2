<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Tambahkan role default
        $roles = ['author', 'reviewer', 'editor', 'conference_manager', 'admin'];
        foreach ($roles as $r) {
            Role::firstOrCreate(['name' => $r]);
        }

        // Buat akun superadmin contoh
        $admin = User::firstOrCreate([
            'email' => 'admin@gmail.com',
            'name' => 'Super Admin',
            'password' => bcrypt('password'),
            'role' => 'conference_manager',
        ]);

        $admin->roles()->attach(Role::where('name', 'conference_manager')->first());

        // Panggil seeder lainnya
        $this->call([
            RoleSeeder::class,
        ]);
    }
}
