<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    public function run()
    {
        // Membuat atau mencari user Admin, lalu tetapkan rolenya
        $adminUser = User::updateOrCreate(
            ['email' => 'admin@gmail.com'], // Cari berdasarkan email
            [
                'name' => 'Admin', // Data untuk dibuat jika belum ada
                'password' => Hash::make('password')
            ]
        );
        $adminUser->assignRole('Admin');

        // Membuat atau mencari user Teknisi, lalu tetapkan rolenya
        $technicianUser = User::updateOrCreate(
            ['email' => 'anthony@gmail.com'], // Cari berdasarkan email
            [
                'name' => 'Anthony', // Data untuk dibuat jika belum ada
                'password' => Hash::make('password')
            ]
        );
        $technicianUser->assignRole('Teknisi');
    }
}
