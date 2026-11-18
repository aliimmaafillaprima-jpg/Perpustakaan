<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin Perpustakaan',
            'email' => 'admin@lendora.com',
            'password' => Hash::make('Admin@123'),
            'role' => 'admin',
            'phone' => '081234567890',
        ]);

        // Siswa contoh
        User::create([
            'name' => 'Andi Pratama',
            'email' => 'andi@lendora.com',
            'password' => Hash::make('Andi@123'),
            'role' => 'siswa',
            'nis' => '12345',
            'kelas' => 'XII RPL B',
            'phone' => '081234567891',
        ]);

        User::create([
            'name' => 'Siti Aminah',
            'email' => 'siti@lendora.com',
            'password' => Hash::make('Siti@123'),
            'role' => 'siswa',
            'nis' => '12346',
            'kelas' => 'XII RPL A',
            'phone' => '081234567892',
        ]);
    }
}