<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->morphs('reviewable'); // reviewable_type + reviewable_id
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('rating'); // 1–5
            $table->string('title', 150)->nullable();
            $table->text('body')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->timestamps();

            // One review per user per reviewable item
            $table->unique(['reviewable_type', 'reviewable_id', 'user_id'], 'reviews_user_reviewable_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
