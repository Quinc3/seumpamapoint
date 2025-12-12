<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('invoice_settings', function (Blueprint $table) {
            // Hapus kolom printer dari invoice_settings
            $table->dropColumn([
                'auto_print',
                'printer_name', 
                'printer_connection',
                'paper_size' // Paper size pindah ke printer_settings
            ]);
        });
    }

    public function down()
    {
        Schema::table('invoice_settings', function (Blueprint $table) {
            $table->boolean('auto_print')->default(false);
            $table->string('printer_name')->nullable();
            $table->string('printer_connection')->default('usb');
            $table->string('paper_size')->default('80mm');
        });
    }
};