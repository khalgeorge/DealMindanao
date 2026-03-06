<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            // Partner type: Partner | Brand | Manufacturer | Distributor
            $table->string('type', 50)->default('Partner')->after('name');
            // Verified badge — admin confirms this is a real, vetted seller
            $table->boolean('verified')->default(false)->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['type', 'verified']);
        });
    }
};
