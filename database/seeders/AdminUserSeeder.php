<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin (yang bisa melihat semua)
        User::create([
            'username' => 'superadmin',
            'email' => 'superadmin@admin.com',
            // Let the User model cast handle hashing (password cast = 'hashed')
            'password' => 'password_super',
            'role_id' => 2, // ID untuk 'superadmin'
            'instansi_id' => null,
        ]);
        
        // Admin Khusus FTMM
        User::create([
            'username' => 'admin_ftmm',
            'email' => 'ftmm@admin.com',
            'password' => 'password_ftmm',
            'role_id' => 3, // ID untuk 'admin_instansi'
            'instansi_id' => 1, // ID untuk 'Fakultas Teknologi Maju dan Multidisiplin'
        ]);

        User::create([
            'username' => 'admin_fst',
            'email' => 'fst@admin.com',
            'password' => 'password_fst',
            'role_id' => 3, // ID untuk 'admin_instansi'
            'instansi_id' => 2, // ID untuk 'Fakultas Sains dan Teknologi'
        ]);
    }
}