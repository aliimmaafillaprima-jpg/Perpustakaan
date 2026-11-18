<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Cek dulu, jika kolom email_verified_at BELUM ada, baru tambahkan
            if (!Schema::hasColumn('users', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable()->after('email');
            }
            
            // Cek dulu, jika remember_token BELUM ada, baru tambahkan
            if (!Schema::hasColumn('users', 'remember_token')) {
                $table->rememberToken();
            }
            
            // Cek dulu, jika deleted_at BELUM ada, baru tambahkan
            if (!Schema::hasColumn('users', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down(): void
    {
        // Bagian down tidak perlu diubah
    }
};