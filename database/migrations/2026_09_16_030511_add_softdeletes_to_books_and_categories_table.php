<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah deleted_at ke tabel books
        Schema::table('books', function (Blueprint $table) {
            if (!Schema::hasColumn('books', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        // Tambah deleted_at ke tabel categories
        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('categories', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};