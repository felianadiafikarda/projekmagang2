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
            ['name' => 'treasure', 'display_name' => 'Treasure', 'level' => 3],
            ['name' => 'secretary', 'display_name' => 'Secretary', 'level' => 3],
            ['name' => 'section_editor', 'display_name' => 'Section Editor', 'level' => 4],
            ['name' => 'editor', 'display_name' => 'Editor', 'level' => 5],
            ['name' => 'conference_manager', 'display_name' => 'Conference Manager', 'level' => 6],
            ['name' => 'admin', 'display_name' => 'Administrator', 'level' => 7],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['name' => $role['name']], $role);
        }
    }
}
