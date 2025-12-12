<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('invoice_settings', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->default('Seumpama Coffee');
            $table->text('company_address')->nullable();
            $table->string('company_phone')->nullable();
            $table->string('company_email')->nullable();
            $table->string('invoice_title')->default('INVOICE');
            $table->text('footer_text')->default('Thank you for your order!');
            $table->text('terms_conditions')->nullable();
            $table->boolean('show_logo')->default(true);
            $table->string('logo_path')->nullable();
            $table->timestamps();
        });

        // Insert default record
        DB::table('invoice_settings')->insert([
            'company_name' => 'Seumpama Coffee',
            'company_address' => 'Your Favorite Coffee Shop',
            'invoice_title' => 'INVOICE',
            'footer_text' => 'Thank you for your order!',
            'show_logo' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('invoice_settings');
    }
};