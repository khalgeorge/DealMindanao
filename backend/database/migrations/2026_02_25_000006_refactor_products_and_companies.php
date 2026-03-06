<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add brand_id FK to products and drop the old free-text brand column
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('brand_id')->nullable()->constrained('brands')->nullOnDelete()->after('supplier_id');
            $table->dropColumn('brand');
        });

        // Drop the 'type' column from companies (Company = Seller only)
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['brand_id']);
            $table->dropColumn('brand_id');
            $table->string('brand')->nullable()->index();
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->string('type')->default('Partner')->after('description');
        });
    }
};
