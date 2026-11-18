<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            // Kolom untuk menyimpan path foto bukti kerusakan
            $table->string('damage_proof')->nullable()->after('condition');
            // Kolom untuk keterangan kerusakan
            $table->text('damage_note')->nullable()->after('damage_proof');
        });
    }

    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropColumn(['damage_proof', 'damage_note']);
        });
    }
};