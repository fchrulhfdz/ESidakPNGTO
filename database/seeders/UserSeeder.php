<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Super Admin
        User::create([
            'name' => 'Super Administrator',
            'email' => 'superadmin@pn-gorontalo.go.id',
            'password' => Hash::make('password123'),
            'role' => 'super_admin',
            'bagian' => null,
        ]);

        // Admin Perdata
        User::create([
            'name' => 'Admin Perdata',
            'email' => 'perdata@pn-gorontalo.go.id',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'bagian' => 'perdata',
        ]);

        // Admin Pidana
        User::create([
            'name' => 'Admin Pidana',
            'email' => 'pidana@pn-gorontalo.go.id',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'bagian' => 'pidana',
        ]);

        // Admin Tipikor
        User::create([
            'name' => 'Admin Tipikor',
            'email' => 'tipikor@pn-gorontalo.go.id',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'bagian' => 'tipikor',
        ]);

        // Admin PHI
        User::create([
            'name' => 'Admin PHI',
            'email' => 'phi@pn-gorontalo.go.id',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'bagian' => 'phi',
        ]);

        // Admin Hukum
        User::create([
            'name' => 'Admin Hukum',
            'email' => 'hukum@pn-gorontalo.go.id',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'bagian' => 'hukum',
        ]);

        // Admin PTIP
        User::create([
            'name' => 'Admin PTIP',
            'email' => 'ptip@pn-gorontalo.go.id',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'bagian' => 'ptip',
        ]);

        // Admin Umum & Keuangan
        User::create([
            'name' => 'Admin Umum & Keuangan',
            'email' => 'umumkeuangan@pn-gorontalo.go.id',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'bagian' => 'umum_keuangan',
        ]);

        // Admin Kepegawaian
        User::create([
            'name' => 'Admin Kepegawaian',
            'email' => 'kepegawaian@pn-gorontalo.go.id',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'bagian' => 'kepegawaian',
        ]);
    }
}