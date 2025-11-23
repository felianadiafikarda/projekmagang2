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
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'email' => 'admin@gmail.com',
                'username' => 'superadmin',
                'password' => Hash::make('password'),
                'affiliation' => null,
                'photo_path' => null,
                'role' => 'admin'
            ],
            [
                'first_name' => 'Conference',
                'last_name' => 'Manager One',
                'email' => 'cm1@gmail.com',
                'username' => 'cm1',
                'password' => Hash::make('password'),
                'affiliation' => 'Universitas Negeri Teknologi',
                'photo_path' => null,
                'role' => 'conference_manager'
            ],
            [
                'first_name' => 'Section',
                'last_name' => 'editor',
                'email' => 'section_editor1@gmail.com',
                'username' => 'section_editor1',
                'password' => Hash::make('password'),
                'affiliation' => 'Institut Teknologi Bandung',
                'photo_path' => null,
                'role' => 'section_editor'
            ],
            [
                'first_name' => 'Editor',
                'last_name' => 'Satu',
                'email' => 'editor1@gmail.com',
                'username' => 'editor1',
                'password' => Hash::make('password'),
                'affiliation' => 'Universitas Informatika Indonesia',
                'photo_path' => null,
                'role' => 'editor'
            ],
            [
                'first_name' => 'Reviewer',
                'last_name' => 'A',
                'email' => 'reviewer1@gmail.com',
                'username' => 'reviewer1',
                'password' => Hash::make('password'),
                'affiliation' => 'Institut Sains dan Data',
                'photo_path' => null,
                'role' => 'reviewer'
            ],
            [
                'first_name' => 'Author',
                'last_name' => 'Test',
                'email' => 'author1@gmail.com',
                'username' => 'author1',
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
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'username' => $data['username'],
                    'password' => $data['password'],
                    'affiliation' => $data['affiliation'],
                    'photo_path' => $data['photo_path'],
                ]
            );

            // Ambil role dan attach (hindari duplikat)
            $role = Role::where('name', $data['role'])->first();

            if ($role && !$user->roles()->where('role_id', $role->id)->exists()) {
                $user->roles()->attach($role);
            }
        }
    }
}
