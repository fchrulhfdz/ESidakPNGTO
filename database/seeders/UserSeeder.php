<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            // Super Admin
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@esidak.com',
                'password' => Hash::make('password'),
                'role' => 'super_admin'
            ],

            // Perkara - Perdata
            [
                'name' => 'Admin Perdata',
                'email' => 'perdata@esidak.com',
                'password' => Hash::make('password'),
                'role' => 'perdata'
            ],

            // Perkara - Pidana
            [
                'name' => 'Admin Pidana',
                'email' => 'pidana@esidak.com',
                'password' => Hash::make('password'),
                'role' => 'pidana'
            ],

            // Perkara - Tipikor
            [
                'name' => 'Admin Tipikor',
                'email' => 'tipikor@esidak.com',
                'password' => Hash::make('password'),
                'role' => 'tipikor'
            ],

            // Perkara - PHI
            [
                'name' => 'Admin PHI',
                'email' => 'phi@esidak.com',
                'password' => Hash::make('password'),
                'role' => 'phi'
            ],

            // Perkara - Hukum
            [
                'name' => 'Admin Hukum',
                'email' => 'hukum@esidak.com',
                'password' => Hash::make('password'),
                'role' => 'hukum'
            ],

            // Kesekretariatan - PTIP
            [
                'name' => 'Admin PTIP',
                'email' => 'ptip@esidak.com',
                'password' => Hash::make('password'),
                'role' => 'ptip'
            ],

            // Kesekretariatan - Umum & Keuangan
            [
                'name' => 'Admin Umum & Keuangan',
                'email' => 'umumkeuangan@esidak.com',
                'password' => Hash::make('password'),
                'role' => 'umum_keuangan'
            ],

            // Kesekretariatan - Kepegawaian
            [
                'name' => 'Admin Kepegawaian',
                'email' => 'kepegawaian@esidak.com',
                'password' => Hash::make('password'),
                'role' => 'kepegawaian'
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}