<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::create(['name' => 'mahasiswa']);      // ID = 1
        Role::create(['name' => 'superadmin']);       // ID = 2
        // Role untuk admin instansi (umum untuk semua instansi)
        Role::create(['name' => 'admin_instansi']); // ID = 3
    }
}