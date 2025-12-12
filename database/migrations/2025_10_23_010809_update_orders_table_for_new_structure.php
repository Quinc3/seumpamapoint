<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Hapus kolom customer_id jika ada dan tidak diperlukan
            if (Schema::hasColumn('orders', 'customer_id')) {
                $table->dropForeign(['customer_id']);
                $table->dropColumn('customer_id');
            }
            
            // Hapus kolom date yang menyebabkan error
            if (Schema::hasColumn('orders', 'date')) {
                $table->dropColumn('date');
            }
            
            // Tambahkan kolom baru hanya jika belum ada
            if (!Schema::hasColumn('orders', 'discount')) {
                $table->decimal('discount', 5, 2)->default(0)->after('total_price');
            }
            
            if (!Schema::hasColumn('orders', 'discount_amount')) {
                $table->decimal('discount_amount', 10, 2)->default(0)->after('discount');
            }
            
            if (!Schema::hasColumn('orders', 'total_payment')) {
                $table->decimal('total_payment', 10, 2)->default(0)->after('discount_amount');
            }
            
            if (!Schema::hasColumn('orders', 'status')) {
                $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending')->after('total_payment');
            }
            
            if (!Schema::hasColumn('orders', 'payment_status')) {
                $table->enum('payment_status', ['paid', 'unpaid'])->default('unpaid')->after('status');
            }
            
            if (!Schema::hasColumn('orders', 'payment_method')) {
                $table->enum('payment_method', ['cash', 'qris', 'debit'])->default('qris')->after('payment_status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Optional: kembalikan struktur sebelumnya jika diperlukan
            // Hanya drop kolom yang kita tambahkan di migration ini
            $columnsToDrop = [
                'discount',
                'discount_amount', 
                'total_payment',
                'status',
                'payment_status',
                'payment_method'
            ];
            
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('orders', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};  