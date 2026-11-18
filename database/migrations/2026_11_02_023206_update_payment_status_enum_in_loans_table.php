<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Mengubah tipe kolom untuk menambahkan nilai 'pending_verification'
        DB::statement("ALTER TABLE loans MODIFY COLUMN payment_status ENUM('none', 'unpaid', 'paid', 'pending_verification') DEFAULT 'none'");
    }

    public function down(): void
    {
        // Mengembalikan ke pengaturan awal
        DB::statement("ALTER TABLE loans MODIFY COLUMN payment_status ENUM('none', 'unpaid', 'paid') DEFAULT 'none'");
    }
};