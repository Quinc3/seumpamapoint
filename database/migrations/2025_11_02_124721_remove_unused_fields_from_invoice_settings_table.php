<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('invoice_settings', function (Blueprint $table) {
            // Hanya hapus font_size (ini ada di tabel)
            $table->dropColumn('font_size');
            
            // JANGAN hapus show_preview karena kolomnya tidak ada
            // $table->dropColumn('show_preview'); // HAPUS BARIS INI
        });
    }

    public function down()
    {
        Schema::table('invoice_settings', function (Blueprint $table) {
            // Tambahkan kembali font_size
            $table->string('font_size')->default('normal');
            
            // Tidak perlu tambah show_preview karena tidak pernah ada
        });
    }
};