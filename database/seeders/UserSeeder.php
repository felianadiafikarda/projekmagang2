<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // List user dummy
        $users = [
            [
                'name' => 'Conference Manager One',
                'email' => 'cm1@gmail.com',
                'password' => Hash::make('password'),
                'affiliation' => 'Universitas Negeri Teknologi',
                'photo_path' => null,
                'role' => 'conference_manager'
            ],
            [
                'name' => 'Editor Satu',
                'email' => 'editor1@gmail.com',
                'password' => Hash::make('password'),
                'affiliation' => 'Universitas Informatika Indonesia',
                'photo_path' => null,
                'role' => 'editor'
            ],
            [
                'name' => 'Reviewer A',
                'email' => 'reviewer1@gmail.com',
                'password' => Hash::make('password'),
                'affiliation' => 'Institut Sains dan Data',
                'photo_path' => null,
                'role' => 'reviewer'
            ],
            [
                'name' => 'Author Test',
                'email' => 'author1@gmail.com',
                'password' => Hash::make('password'),
                'affiliation' => 'Universitas Teknik Komputer',
                'photo_path' => null,
                'role' => 'author'
            ],
        ];

        foreach ($users as $data) {
            // Buat user jika belum ada
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => $data['password'],
                    'affiliation' => $data['affiliation'],
                    'photo_path' => $data['photo_path'],
                ]
            );

            // Ambil role dan attach (cek supaya tidak duplikat)
            $role = Role::where('name', $data['role'])->first();
            if ($role && !$user->roles()->where('role_id', $role->id)->exists()) {
                $user->roles()->attach($role);
            }
        }
    }
}
