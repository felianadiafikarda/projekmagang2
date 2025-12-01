<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PreparedEmail;
use Symfony\Component\Mime\Email;

class PreparedEmailSeeder extends Seeder
{
    public function run(): void
    {
        PreparedEmail::insert([
            [
                'email_template' => 'reminder',
                'sender' => 'Admin',
                'recipient' => 'Editor',
                'subject' => 'Pengingat Jadwal Pendadaran',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'email_template' => 'registration',
                'sender' => 'Editor',
                'recipient' => 'Section Editor',
                'subject' => 'Pendaftaran Berhasil',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'email_template' => 'announcement',
                'sender' => 'Section Editor',
                'recipient' => 'Reviewer',
                'subject' => 'Pengumuman Penting',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
