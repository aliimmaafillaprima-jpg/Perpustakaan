<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('title');
            $table->string('author');
            $table->string('publisher')->nullable();
            $table->year('year')->nullable();
            $table->string('isbn')->nullable();
            $table->integer('stock')->default(0);
            $table->string('cover')->nullable();
            $table->string('qr_code')->nullable(); // <-- Kolom ini sekarang aman di sini
            $table->text('description')->nullable();
            $table->softDeletes(); // <-- Untuk fitur hapus sementara
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};