<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Hapus field yang tidak perlu untuk coffee shop simple
            if (Schema::hasColumn('orders', 'customer_id')) {
                $table->dropForeign(['customer_id']);
                $table->dropColumn('customer_id');
            }
            
            if (Schema::hasColumn('orders', 'date')) {
                $table->dropColumn('date');
            }
            
            // Kita keep discount_amount karena digunakan dalam calculation
        });
    }

    public function down(): void
    {
        // Optional: bisa ditambahkan kembali jika perlu
    }
};