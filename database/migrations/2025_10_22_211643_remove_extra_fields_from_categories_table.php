<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Jika ada field tambahan yang sebelumnya ditambahkan, hapus
        if (Schema::hasColumn('categories', 'description')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropColumn('description');
            });
        }
        
        if (Schema::hasColumn('categories', 'sort_order')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropColumn('sort_order');
            });
        }
        
        if (Schema::hasColumn('categories', 'color')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropColumn('color');
            });
        }
        
        if (Schema::hasColumn('categories', 'icon')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropColumn('icon');
            });
        }
    }

    public function down(): void
    {
        // Optional: bisa ditambahkan kembali jika perlu rollback
    }
};