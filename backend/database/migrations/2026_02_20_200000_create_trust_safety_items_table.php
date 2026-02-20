<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trust_safety_items', function (Blueprint $table) {
            $table->id();
            $table->text('icon_svg')->nullable();
            $table->string('icon_color', 20)->default('brand'); // 'brand' or 'accent'
            $table->string('title', 300);
            $table->text('description');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trust_safety_items');
    }
};
