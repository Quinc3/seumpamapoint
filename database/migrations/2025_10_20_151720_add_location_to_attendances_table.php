<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            if (!Schema::hasColumn('attendances', 'latitude')) {
                $table->decimal('latitude', 10, 7)->nullable()->after('shift');
            }
            if (!Schema::hasColumn('attendances', 'longitude')) {
                $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            }
            if (!Schema::hasColumn('attendances', 'status')) {
                $table->string('status')->nullable()->after('longitude'); // optional: 'valid' or 'out_of_area'
            }
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            if (Schema::hasColumn('attendances', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('attendances', 'longitude')) {
                $table->dropColumn('longitude');
            }
            if (Schema::hasColumn('attendances', 'latitude')) {
                $table->dropColumn('latitude');
            }
        });
    }
};
