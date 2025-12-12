<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('invoice_settings', function (Blueprint $table) {
            $table->enum('paper_size', ['58mm', '80mm', 'A4'])->default('80mm')->after('terms_conditions');
        });
    }

    public function down()
    {
        Schema::table('invoice_settings', function (Blueprint $table) {
            $table->dropColumn('paper_size');
        });
    }
};