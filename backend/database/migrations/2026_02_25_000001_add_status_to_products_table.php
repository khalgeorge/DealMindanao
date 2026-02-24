<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Adds a `status` workflow column to the products table.
     *
     * Values:
     *   draft     – being prepared; never shown on the public shop
     *   published – live and visible to customers (also requires is_active=true)
     *
     * Migration strategy for existing rows:
     *   is_active = true  → status = 'published'  (they were already live)
     *   is_active = false → status = 'draft'       (they were hidden)
     *
     * New products always default to 'draft' so admins must explicitly publish them.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('status', 20)
                  ->default('draft')
                  ->after('is_active')
                  ->comment('draft|published — workflow state; new products default to draft');
        });

        // Respect the visibility state of existing products.
        DB::table('products')->where('is_active', true)->update(['status' => 'published']);
        // is_active = false rows already get 'draft' from the column default.
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
