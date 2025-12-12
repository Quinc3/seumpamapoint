<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Hapus foreign key constraint terlebih dahulu
        Schema::table('products', function (Blueprint $table) {
            // Cek jika foreign key exists lalu drop
            if (Schema::hasColumn('products', 'subcategory_id')) {
                $table->dropForeign(['subcategory_id']);
            }
        });

        // Kemaha hapus kolom
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'subcategory_id')) {
                $table->dropColumn('subcategory_id');
            }

        });
    }

    public function down()
    {
        // Optional: define how to reverse this if needed
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('subcategory_id')->nullable();
            $table->foreign('subcategory_id')->references('id')->on('sub_categories');
        });
    }
};