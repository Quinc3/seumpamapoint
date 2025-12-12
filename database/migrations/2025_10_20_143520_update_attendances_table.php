<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {

            $table->enum('shift', ['shift1', 'shift2'])->nullable()->change();

            $table->enum('status', ['clock_in', 'clock_out'])->nullable()->after('clock_out');

            $table->integer('radius')->nullable()->after('status');

            $table->string('address')->nullable()->after('radius');
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['status', 'radius', 'address']);
        });
    }
};