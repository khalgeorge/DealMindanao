<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Add new columns
            $table->foreignId('user_id')->after('id')->constrained()->cascadeOnDelete();
            $table->decimal('total', 10, 2)->after('order_number');
            $table->string('payment_method')->after('status');
            $table->string('shipping_address')->after('payment_method');
            $table->string('shipping_city')->after('shipping_address');
            $table->string('shipping_province')->after('shipping_city');
            $table->string('shipping_postal_code')->after('shipping_province');
            $table->string('tracking_number')->nullable()->after('shipping_postal_code');
            
            // Drop old columns
            $table->dropColumn(['customer_name', 'email', 'address']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Restore old columns
            $table->string('customer_name')->after('order_number');
            $table->string('email')->after('customer_name');
            $table->string('address')->after('email');
            
            // Drop new columns
            $table->dropForeign(['user_id']);
            $table->dropColumn([
                'user_id',
                'total',
                'payment_method',
                'shipping_address',
                'shipping_city',
                'shipping_province',
                'shipping_postal_code',
                'tracking_number',
            ]);
        });
    }
};
