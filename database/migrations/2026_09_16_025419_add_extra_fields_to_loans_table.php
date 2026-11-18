<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            if (!Schema::hasColumn('loans', 'condition')) {
                $table->string('condition')->nullable()->after('status');
            }
            if (!Schema::hasColumn('loans', 'late_days')) {
                $table->integer('late_days')->default(0)->after('condition');
            }
            if (!Schema::hasColumn('loans', 'fine')) {
                $table->decimal('fine', 10, 2)->default(0)->after('late_days');
            }
            if (!Schema::hasColumn('loans', 'payment_status')) {
                $table->enum('payment_status', ['none', 'unpaid', 'paid'])->default('none')->after('fine');
            }
            if (!Schema::hasColumn('loans', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('payment_status');
            }
            if (!Schema::hasColumn('loans', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('paid_at');
            }
            if (!Schema::hasColumn('loans', 'payment_notes')) {
                $table->text('payment_notes')->nullable()->after('payment_method');
            }
            if (!Schema::hasColumn('loans', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropColumn([
                'condition', 'late_days', 'fine', 'payment_status',
                'paid_at', 'payment_method', 'payment_notes', 'deleted_at'
            ]);
        });
    }
};