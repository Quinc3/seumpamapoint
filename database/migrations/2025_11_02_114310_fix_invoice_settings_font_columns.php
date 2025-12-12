<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Hanya tambahkan kolom yang belum ada
        if (!Schema::hasColumn('invoice_settings', 'font_size')) {
            Schema::table('invoice_settings', function (Blueprint $table) {
                $table->string('font_size')->default('normal')->after('terms_conditions');
            });
        }

        if (!Schema::hasColumn('invoice_settings', 'font_family')) {
            Schema::table('invoice_settings', function (Blueprint $table) {
                $table->string('font_family')->default('Courier New')->after('font_size');
            });
        }

        if (!Schema::hasColumn('invoice_settings', 'paper_size')) {
            Schema::table('invoice_settings', function (Blueprint $table) {
                $table->string('paper_size')->default('80mm')->after('font_family');
            });
        }
    }

    public function down(): void
    {
        // Optional: Hapus kolom jika diperlukan
    }
};