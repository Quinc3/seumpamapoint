<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Hanya tambahkan category_id (karena tabel categories sudah ada)
            $table->foreignId('category_id')->nullable()->constrained()->cascadeOnDelete();
            
            // Hapus brand_id dan subcategory_id jika tidak ingin menggunakannya
            // $table->foreignId('brand_id')->nullable()->constrained()->cascadeOnDelete();
            // $table->foreignId('subcategory_id')->nullable()->constrained('sub_categories')->cascadeOnDelete();
            
            $table->boolean('is_active')->default(true);
            $table->boolean('in_stock')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn(['category_id', 'is_active', 'in_stock']);
        });
    }
};