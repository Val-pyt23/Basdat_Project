<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Instansi;

class InstansiSeeder extends Seeder
{
    public function run(): void
    {
        // Fakultas
        Instansi::create(['name' => 'Fakultas Teknologi Maju dan Multidisiplin', 'type' => 'fakultas']);
        Instansi::create(['name' => 'Fakultas Sains Teknologi', 'type' => 'fakultas']);
        // ... Tambahkan semua fakultas lainnya dengan type 'fakultas'

        // Perpustakaan
        Instansi::create(['name' => 'Perpustakaan A', 'type' => 'perpustakaan']);
        Instansi::create(['name' => 'Perpustakaan B', 'type' => 'perpustakaan']);
        Instansi::create(['name' => 'Perpustakaan C', 'type' => 'perpustakaan']);

        // Lainnya
        Instansi::create(['name' => 'Masjid A', 'type' => 'lainnya']);
    }
}