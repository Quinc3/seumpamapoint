<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Pilih salah satu struktur yang sesuai:
            
            // OPTION 1: String untuk multi role
            $table->string('role')->default('user')->after('email');
            // atau
            // $table->enum('role', ['admin', 'user', 'editor'])->default('user');
            
            // OPTION 2: Boolean untuk admin saja
            // $table->boolean('is_admin')->default(false)->after('email');
            
            // OPTION 3: Integer untuk level
            // $table->tinyInteger('role_level')->default(1)->comment('1=user, 2=moderator, 3=admin');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role']); // sesuaikan dengan nama kolom
        });
    }
};