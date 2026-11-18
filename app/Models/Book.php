<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Book extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id', 'title', 'author', 'publisher',
        'year', 'isbn', 'stock', 'cover', 'description', 'qr_code',
    ];

    protected static function booted(): void
    {
        static::creating(function ($book) {
            if (empty($book->qr_code)) {
                $book->qr_code = 'BOOK-' . strtoupper(Str::random(8));
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function isAvailable(): bool
    {
        return $this->stock > 0;
    }

    // FUNGSI UNTUK MEMBACA GAMBAR DARI INTERNET DAN FILE UPLOAD
    public function getCoverUrlAttribute(): string
    {
        if ($this->cover) {
            // Jika cover berupa link internet (http/https)
            if (str_starts_with($this->cover, 'http')) {
                return $this->cover;
            }
            // Jika cover berupa file yang di-upload dari laptop
            if (file_exists(storage_path('app/public/' . $this->cover))) {
                return asset('storage/' . $this->cover);
            }
        }

        $legacyCovers = [
            'Bumi' => 'bumi_tere_liye.jpg',
            'Harry Potter 1' => 'harry_porter.jpg',
            'Ayat-Ayat Cinta' => 'ayat-ayat_cinta.jpg',
            'Rembulan Tenggelam' => 'rembulan_tenggelam_diwajahmu.png',
            'Filosofi Teras' => 'filosofit_teras.jpg',
            'Internet of Things' => 'internet_of_things_(IoT).jpg',
            'Pemrograman Web PHP' => 'pemrograman_web_dengan_PHP&MySQL.jpg',
            'Habibie & Ainun' => 'habibie&ainun.jpg',
            'Elon Musk' => 'elon_musk.jpg',
            'KBBI' => 'kamus_besar_bahasa_indonesia_(KBBI)_edisi_V.jpg',
            'Ensiklopedia Pendidikan' => 'ensiklopedia_pendidikan&psikologi.jpg',
        ];

        if (isset($legacyCovers[$this->title]) && file_exists(public_path('images/books/' . $legacyCovers[$this->title]))) {
            return asset('images/books/' . $legacyCovers[$this->title]);
        }

        return asset('images/book-placeholder.svg');
    }
}