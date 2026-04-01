<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            // Drop the foreign key constraint before making user_id nullable
            $table->dropForeign(['user_id']);

            // Allow user_id to be null (for guest messages)
            $table->unsignedBigInteger('user_id')->nullable()->change();

            // Re-add foreign key with cascade delete (null-safe)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Add guest token — a UUID stored in the visitor's localStorage
            $table->string('guest_token', 64)->nullable()->after('user_id')->index();
        });
    }

    public function down(): void
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('guest_token');
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
