<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('discount', 8, 2)->default(0)->change();
            $table->decimal('discount_amount', 8, 2)->default(0)->change();
            $table->decimal('cash_received', 12, 2)->nullable()->change();
            $table->decimal('cash_change', 12, 2)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('discount', 8, 2)->default(null)->change();
            $table->decimal('discount_amount', 8, 2)->default(null)->change();
            $table->decimal('cash_received', 12, 2)->change();
            $table->decimal('cash_change', 12, 2)->change();
        });
    }
};