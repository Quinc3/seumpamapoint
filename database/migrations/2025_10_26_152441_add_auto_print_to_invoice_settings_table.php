<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('invoice_settings', function (Blueprint $table) {
            $table->boolean('auto_print')->default(false)->after('paper_size');
            $table->string('printer_name')->nullable()->after('auto_print');
            $table->string('printer_connection')->default('bluetooth')->after('printer_name');
        });
    }

    public function down()
    {
        Schema::table('invoice_settings', function (Blueprint $table) {
            $table->dropColumn(['auto_print', 'printer_name', 'printer_connection']);
        });
    }
};