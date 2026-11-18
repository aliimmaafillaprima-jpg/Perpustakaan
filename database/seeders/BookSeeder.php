<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Loan;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Bersihkan data lama (Agar katalog 100% baru)
        Loan::query()->forceDelete();
        Book::query()->forceDelete();

        // 2. Ambil ID Kategori
        $catFiksi = Category::where('name', 'Fiksi & Novel')->first()->id;
        $catNonFiksi = Category::where('name', 'Non-Fiksi & Pengembangan Diri')->first()->id;
        $catTeknologi = Category::where('name', 'Sains & Teknologi')->first()->id;
        $catAgama = Category::where('name', 'Agama & Spiritual')->first()->id;
        $catSejarah = Category::where('name', 'Sejarah & Biografi')->first()->id;
        $catReferensi = Category::where('name', 'Referensi & Ensiklopedia')->first()->id;
        $catBudaya = Category::where('name', 'Budaya & Sosial')->first()->id;

        // 3. Daftar 50 Buku (Judul sudah disingkat jika kepanjangan)
        $books = [
            // FIKSI & NOVEL (18)
            ['category_id' => $catFiksi, 'title' => 'Laskar Pelangi', 'author' => 'Andrea Hirata', 'filename' => 'laskar_pelangi'],
            ['category_id' => $catFiksi, 'title' => 'Bumi Manusia', 'author' => 'Pramoedya Ananta Toer', 'filename' => 'bumi_manusia'],
            ['category_id' => $catFiksi, 'title' => 'Bumi', 'author' => 'Tere Liye', 'filename' => 'bumi_tere_liye'],
            ['category_id' => $catFiksi, 'title' => 'Dilan 1990', 'author' => 'Pidi Baiq', 'filename' => 'dilan_1990'],
            ['category_id' => $catFiksi, 'title' => 'Perahu Kertas', 'author' => 'Dee Lestari', 'filename' => 'perahu_kertas'],
            ['category_id' => $catFiksi, 'title' => 'Harry Potter 1', 'author' => 'J.K. Rowling', 'filename' => 'harry_porter'],
            ['category_id' => $catFiksi, 'title' => 'The Hobbit', 'author' => 'J.R.R. Tolkien', 'filename' => 'the_hobbit'],
            ['category_id' => $catFiksi, 'title' => 'The Alchemist', 'author' => 'Paulo Coelho', 'filename' => 'the_alchemist'],
            ['category_id' => $catFiksi, 'title' => 'Ayat-Ayat Cinta', 'author' => 'Habiburrahman El Shirazy', 'filename' => 'ayat-ayat_cinta'],
            ['category_id' => $catFiksi, 'title' => 'Rembulan Tenggelam', 'author' => 'Tere Liye', 'filename' => 'rembulan_tenggelam_diwajahmu'],
            ['category_id' => $catFiksi, 'title' => 'Pulang', 'author' => 'Leila S. Chudori', 'filename' => 'pulang'],
            ['category_id' => $catFiksi, 'title' => 'Cantik Itu Luka', 'author' => 'Eka Kurniawan', 'filename' => 'cantik_itu_luka'],
            ['category_id' => $catFiksi, 'title' => 'The Prophet', 'author' => 'Khalil Gibran', 'filename' => 'the_prophet'],
            ['category_id' => $catFiksi, 'title' => 'Negeri 5 Menara', 'author' => 'Ahmad Fuadi', 'filename' => 'negeri_5_menara'],
            ['category_id' => $catFiksi, 'title' => 'Ranah 3 Warna', 'author' => 'Ahmad Fuadi', 'filename' => 'ranah_3_warna'],
            ['category_id' => $catFiksi, 'title' => 'The Little Prince', 'author' => 'Antoine de Saint-Exupéry', 'filename' => 'the_little_prince'],
            ['category_id' => $catFiksi, 'title' => 'Animal Farm', 'author' => 'George Orwell', 'filename' => 'animal_farm'],
            ['category_id' => $catFiksi, 'title' => '1984', 'author' => 'George Orwell', 'filename' => '1984'],

            // NON-FIKSI (12)
            ['category_id' => $catNonFiksi, 'title' => 'Atomic Habits', 'author' => 'James Clear', 'filename' => 'atomic_habits'],
            ['category_id' => $catNonFiksi, 'title' => 'Filosofi Teras', 'author' => 'Henry Manampiring', 'filename' => 'filosofit_teras'],
            ['category_id' => $catNonFiksi, 'title' => 'Sapiens', 'author' => 'Yuval Noah Harari', 'filename' => 'sapiens'],
            ['category_id' => $catNonFiksi, 'title' => 'Homo Deus', 'author' => 'Yuval Noah Harari', 'filename' => 'homo_deus'],
            ['category_id' => $catNonFiksi, 'title' => 'Seni Bodo Amat', 'author' => 'Mark Manson', 'filename' => 'sebuah_seni_untuk_bersikap_bodo_amat'],
            ['category_id' => $catNonFiksi, 'title' => 'Manusia Mencari Dirinya', 'author' => 'Rollo May', 'filename' => 'manusia_mencari_dirinya'],
            ['category_id' => $catNonFiksi, 'title' => 'Design Thinking', 'author' => 'Jamila Badern', 'filename' => 'design_thinking'],
            ['category_id' => $catNonFiksi, 'title' => 'Deep Work', 'author' => 'Cal Newport', 'filename' => 'deep_work'],
            ['category_id' => $catNonFiksi, 'title' => 'Rich Dad Poor Dad', 'author' => 'Robert T. Kiyosaki', 'filename' => 'rich_dad_poor_dad'],
            ['category_id' => $catNonFiksi, 'title' => 'How to Win Friends', 'author' => 'Dale Carnegie', 'filename' => 'how_to_win_friends_and_influence_people'],
            ['category_id' => $catNonFiksi, 'title' => 'Mindset', 'author' => 'Carol S. Dweck', 'filename' => 'mindset'],
            ['category_id' => $catNonFiksi, 'title' => 'Grit', 'author' => 'Angela Duckworth', 'filename' => 'grit'],

            // SAINS & TEKNOLOGI (6)
            ['category_id' => $catTeknologi, 'title' => 'Cosmos', 'author' => 'Carl Sagan', 'filename' => 'cosmos'],
            ['category_id' => $catTeknologi, 'title' => 'Sejarah Singkat Waktu', 'author' => 'Stephen Hawking', 'filename' => 'a_brief_history_of_time'],
            ['category_id' => $catTeknologi, 'title' => 'Clean Code', 'author' => 'Robert C. Martin', 'filename' => 'clean_code'],
            ['category_id' => $catTeknologi, 'title' => 'Internet of Things', 'author' => 'Nurhidayati', 'filename' => 'internet_of_things_(IoT)'],
            ['category_id' => $catTeknologi, 'title' => 'Pemrograman Web PHP', 'author' => 'Abdul Kadir', 'filename' => 'pemrograman_web_dengan_PHP&MySQL'],
            ['category_id' => $catTeknologi, 'title' => 'Algoritma Pemrograman', 'author' => 'Rinaldi Munir', 'filename' => 'algoritma_dan_pemrograman'],

            // AGAMA (4)
            ['category_id' => $catAgama, 'title' => 'Riyadhus Shalihin', 'author' => 'Imam Nawawi', 'filename' => 'riyadhus_shalihin'],
            ['category_id' => $catAgama, 'title' => 'Adab Di Atas Ilmu', 'author' => 'Imam Nawawi', 'filename' => 'adab_di_atas_ilmu'],
            ['category_id' => $catAgama, 'title' => '100 Tokoh Islam', 'author' => 'Mufth. Iqbal', 'filename' => '100_tokoh_islam_terhebat_dalam_sejarah'],
            ['category_id' => $catAgama, 'title' => 'Empat Imam Mazhab', 'author' => 'Ibrahim W', 'filename' => 'empat_imam_mazhab_yang_mengempurani_dunia'],

            // SEJARAH & BIOGRAFI (5)
            ['category_id' => $catSejarah, 'title' => 'Soekarno', 'author' => 'Cindy Adams', 'filename' => 'soekarno_penyambung_lidah_rakyat'],
            ['category_id' => $catSejarah, 'title' => 'Habibie & Ainun', 'author' => 'B.J. Habibie', 'filename' => 'habibie&ainun'],
            ['category_id' => $catSejarah, 'title' => 'Steve Jobs', 'author' => 'Walter Isaacson', 'filename' => 'steve_jobs'],
            ['category_id' => $catSejarah, 'title' => 'Sejarah Indonesia Modern', 'author' => 'M.C. Ricklefs', 'filename' => 'sejarah_indonesia_modern'],
            ['category_id' => $catSejarah, 'title' => 'Elon Musk', 'author' => 'Ashlee Vance', 'filename' => 'elon_musk'],

            // REFERENSI (4)
            ['category_id' => $catReferensi, 'title' => 'KBBI', 'author' => 'Badan Bahasa', 'filename' => 'kamus_besar_bahasa_indonesia_(KBBI)_edisi_V'],
            ['category_id' => $catReferensi, 'title' => 'Kamus Inggris-Indonesia', 'author' => 'Erlangga', 'filename' => 'kamus_lengkap_inggris_indonesia'],
            ['category_id' => $catReferensi, 'title' => 'Ensiklopedia Pendidikan', 'author' => 'Zainal Aqib', 'filename' => 'ensiklopedia_pendidikan&psikologi'],
            ['category_id' => $catReferensi, 'title' => 'Atlas Sejarah Dunia', 'author' => 'DK Publishing', 'filename' => 'atlas_sejarah_dunia'],

            // BUDAYA (1)
            ['category_id' => $catBudaya, 'title' => 'Budaya Indonesia', 'author' => 'Tim Penulis', 'filename' => 'budaya_indonesia'],
        ];

        // 4. Proses Simpan & Copy Gambar Otomatis
        foreach ($books as $book) {
            $coverPath = null;
            $sourceDir = public_path('images/books/');
            
            // Cek apakah file gambar ada (baik .jpg, .jpeg, atau .png)
            $extensions = ['jpg', 'jpeg', 'png', 'webp'];
            foreach ($extensions as $ext) {
                $sourceFile = $sourceDir . $book['filename'] . '.' . $ext;
                if (File::exists($sourceFile)) {
                    // Tentukan nama file di storage
                    $destFileName = 'covers/' . $book['filename'] . '.' . $ext;
                    // Copy file ke storage/app/public/covers/
                    Storage::disk('public')->put($destFileName, File::get($sourceFile));
                    $coverPath = $destFileName;
                    break;
                }
            }

            // Simpan ke database
            Book::create([
                'category_id' => $book['category_id'],
                'title' => $book['title'],
                'author' => $book['author'],
                'publisher' => '-',
                'year' => 2024,
                'stock' => 10, // Stok default
                'description' => 'Buku ' . $book['title'] . ' yang tersedia di perpustakaan.',
                'cover' => $coverPath, // Hasil copy gambar
            ]);
        }
    }
}