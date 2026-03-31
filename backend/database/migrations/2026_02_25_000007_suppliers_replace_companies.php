<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // DB state at this point:
        //   - suppliers table already exists (was previously companies, renamed in an earlier migration)
        //     columns: id, name, contact_person, city, province, contact_email, contact_phone,
        //              messenger_link, description, logo, is_active, verified, created_at, updated_at
        //   - products table still has company_id (not yet renamed)

        // ── 1. Clean up suppliers table ───────────────────────────────────────
        Schema::table('suppliers', function (Blueprint $table) {
            // Add internal_notes after contact_phone (safe anchor that exists)
            if (!Schema::hasColumn('suppliers', 'internal_notes')) {
                $table->text('internal_notes')->nullable()->after('contact_phone');
            }

            // Rename verified → is_verified (only if not already renamed)
            if (Schema::hasColumn('suppliers', 'verified') && !Schema::hasColumn('suppliers', 'is_verified')) {
                $table->renameColumn('verified', 'is_verified');
            }

            // Drop columns that no longer belong in the supplier model (only if they exist)
            $dropCols = array_filter(['city', 'province', 'messenger_link', 'logo', 'description'], fn($col) => Schema::hasColumn('suppliers', $col));
            if (!empty($dropCols)) {
                $table->dropColumn(array_values($dropCols));
            }
        });

        // ── 2. Rename products.company_id → supplier_id (if not already done) ──
        if (Schema::hasColumn('products', 'company_id') && !Schema::hasColumn('products', 'supplier_id')) {
            Schema::table('products', function (Blueprint $table) {
                $table->renameColumn('company_id', 'supplier_id');
            });
        }
    }

    public function down(): void
    {
        // ── 2 reversed: rename products.supplier_id → company_id ─────────────
        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('supplier_id', 'company_id');
        });

        // ── 1 reversed: restore suppliers columns ────────────────────────────
        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('messenger_link')->nullable();
            $table->string('logo')->nullable();
            $table->text('description')->nullable();
            $table->renameColumn('is_verified', 'verified');
            $table->dropColumn('internal_notes');
        });
    }
};
