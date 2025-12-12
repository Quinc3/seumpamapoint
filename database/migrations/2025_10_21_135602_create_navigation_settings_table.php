<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('navigation_settings', function (Blueprint $table) {
            $table->id();
            $table->string('resource_name')->unique(); // sub_category, brand, category
            $table->string('display_name'); // Nama yang ditampilkan
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Insert default data
        DB::table('navigation_settings')->insert([
            ['resource_name' => 'sub_category', 'display_name' => 'Sub Categories', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['resource_name' => 'brand', 'display_name' => 'Brands', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['resource_name' => 'category', 'display_name' => 'Categories', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('navigation_settings');
    }
};