<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Hapus kategori lama (hati-hati, pastikan tidak ada relasi yang error)
        Category::query()->forceDelete();

        $categories = [
            'Fiksi & Novel',
            'Non-Fiksi & Pengembangan Diri',
            'Sains & Teknologi',
            'Agama & Spiritual',
            'Sejarah & Biografi',
            'Referensi & Ensiklopedia',
            'Budaya & Sosial' // Kategori baru untuk Budaya Indonesia
        ];

        foreach ($categories as $cat) {
            Category::create(['name' => $cat, 'description' => 'Kategori ' . $cat]);
        }
    }
}