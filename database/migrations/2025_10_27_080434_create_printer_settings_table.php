<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('printer_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('auto_print')->default(false);
            $table->string('printer_name')->nullable();
            $table->string('printer_connection')->default('usb');
            $table->string('paper_size')->default('80mm');
            $table->integer('copies')->default(1);
            $table->boolean('test_mode')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('printer_settings');
    }
};