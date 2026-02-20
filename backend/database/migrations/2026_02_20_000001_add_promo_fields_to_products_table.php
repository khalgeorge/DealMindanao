<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds three columns that power the promo / discount system:
     *   promo_label     – short badge text shown on product cards ("Flash Sale", etc.)
     *   promo_starts_at – optional activation datetime (null = active immediately)
     *   promo_ends_at   – optional expiry datetime (null = never expires)
     *
     * The existing `discount` column (flat ₱ amount) is unchanged.
     * A promo is considered ACTIVE when:
     *   - discount > 0
     *   - now >= promo_starts_at (or promo_starts_at is null)
     *   - now <= promo_ends_at   (or promo_ends_at is null)
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('promo_label', 60)->nullable()->after('discount');
            $table->timestamp('promo_starts_at')->nullable()->after('promo_label');
            $table->timestamp('promo_ends_at')->nullable()->after('promo_starts_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['promo_label', 'promo_starts_at', 'promo_ends_at']);
        });
    }
};
