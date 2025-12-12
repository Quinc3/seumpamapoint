<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Susu, Kopi, Gula, dll
            $table->string('unit'); // ml, gram, pcs, etc (bisa custom)
            $table->decimal('stock', 10, 2)->default(0); // 1000.00
            $table->decimal('min_stock', 10, 2)->default(0); // Alert stok rendah
            $table->decimal('cost_per_unit', 10, 2)->default(0); // Harga per unit
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ingredients');
    }
};