<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category; // Jangan lupa import model Category

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus semua kategori lama dan ganti dengan yang baru
        Category::create(['name' => 'Sarana Prasarana']);
        Category::create(['name' => 'Keuangan']);
        Category::create(['name' => 'Persuratan']);
        Category::create(['name' => 'Akademik']);
        Category::create(['name' => 'Lainnya']);
    }
}