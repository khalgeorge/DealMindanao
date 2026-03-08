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
        Schema::table('products', function (Blueprint $table) {
            // Most-used filter columns — full table scans without these
            $table->index('is_active',   'products_is_active_index');
            $table->index('status',      'products_status_index');
            $table->index('is_featured', 'products_is_featured_index');
            // Composite: the shop and home page always filter by both
            $table->index(['is_active', 'status'], 'products_active_status_index');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_is_active_index');
            $table->dropIndex('products_status_index');
            $table->dropIndex('products_is_featured_index');
            $table->dropIndex('products_active_status_index');
        });
    }
};
