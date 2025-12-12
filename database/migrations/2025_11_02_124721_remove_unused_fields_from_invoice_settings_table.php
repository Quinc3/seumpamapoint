<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('invoice_settings', function (Blueprint $table) {
            // Hapus field yang tidak digunakan
            $table->dropColumn(['font_size', 'show_preview']);
        });
    }

    public function down()
    {
        Schema::table('invoice_settings', function (Blueprint $table) {
            $table->string('font_size')->default('normal')->nullable();
            $table->boolean('show_preview')->default(true);
        });
    }
};