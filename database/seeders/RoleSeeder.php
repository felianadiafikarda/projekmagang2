<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'author', 'display_name' => 'Author', 'level' => 1],
            ['name' => 'reviewer', 'display_name' => 'Reviewer', 'level' => 2],
            ['name' => 'editor', 'display_name' => 'Editor', 'level' => 3],
            ['name' => 'conference_manager', 'display_name' => 'Conference Manager', 'level' => 4],
            ['name' => 'admin', 'display_name' => 'Admin', 'level' => 5],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['name' => $role['name']], $role);
        }
    }
}
