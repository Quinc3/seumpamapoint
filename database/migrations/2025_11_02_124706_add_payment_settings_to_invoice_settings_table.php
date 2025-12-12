<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('invoice_settings', function (Blueprint $table) {
            $table->boolean('show_cash_details')->default(true)->after('logo_path');
            $table->boolean('show_payment_summary')->default(true)->after('show_cash_details');
            $table->boolean('auto_calculate_change')->default(true)->after('show_payment_summary');
        });
    }

    public function down()
    {
        Schema::table('invoice_settings', function (Blueprint $table) {
            $table->dropColumn(['show_cash_details', 'show_payment_summary', 'auto_calculate_change']);
        });
    }
};