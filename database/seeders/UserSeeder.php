<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash; // Penting untuk enkripsi password

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Membuat 1 user mahasiswa
        User::create([
            'username' => 'mahasiswa_test',
            'email' => 'mahasiswa@test.com',
            // Plain password so User model hash cast will apply
            'password' => 'password_test_1', // passwordnya: "password"
            'role_id' => 1, // Asumsi 'mahasiswa' memiliki role_id = 1
            'instansi_id' => 1, // Asumsi 'Fakultas Teknik' memiliki instansi_id = 1
            'phone_number' => '08123456789',
        ]);

        User::create([
            'username' => 'mahasiswa_test_2',
            'email' => 'mahasiswa2@test.com',
            // Plain password so User model hash cast will apply
            'password' => 'password_test_2', // passwordnya: "password"
            'role_id' => 1, // Asumsi 'mahasiswa' memiliki role_id = 1
            'instansi_id' => 1, // Asumsi 'Fakultas Teknik' memiliki instansi_id = 1
            'phone_number' => '08123456990',
        ]);
    }
}