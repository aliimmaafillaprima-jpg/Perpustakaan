<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Cek dulu, jika kolom jurusan belum ada, baru tambahkan
            if (!Schema::hasColumn('users', 'jurusan')) {
                $table->string('jurusan')->nullable()->after('kelas');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('jurusan');
        });
    }
};