<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Tambahkan kolom user_id sebagai foreign key ke tabel users
            $table->foreignId('user_id')->nullable()->constrained('users')->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Hapus foreign key dan kolom user_id
            $table->dropConstrainedForeignId('user_id');
        });
    }
};
