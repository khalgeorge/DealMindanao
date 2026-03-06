<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds product-identification fields (brand, model_code, variant) and
     * separates the cost basis (supplier_price) from the customer-facing
     * selling price (srp).
     *
     * The legacy `price` column is kept untouched for backward compatibility
     * with existing queries (e.g. OrderItems that captured price at checkout).
     * Going forward, `price` is kept in sync with `srp` by the Product model.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Product identification
            $table->string('brand')->nullable()->index()->after('name');
            $table->string('model_code')->nullable()->index()->after('brand');
            $table->string('variant')->nullable()->after('model_code');

            // Pricing split
            $table->decimal('supplier_price', 10, 2)->nullable()->after('price');
            $table->decimal('srp', 10, 2)->nullable()->after('supplier_price');
        });

        // Back-fill srp from the existing price column so that old records keep
        // displaying correctly without any manual admin intervention.
        DB::table('products')->whereNotNull('price')->update([
            'srp' => DB::raw('`price`'),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['brand', 'model_code', 'variant', 'supplier_price', 'srp']);
        });
    }
};
