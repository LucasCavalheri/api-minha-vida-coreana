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
        Schema::create('comments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->text('content');
            $table->uuid('user_id');
            $table->uuid('post_id');
            $table->uuid('parent_id')->nullable();
            $table->timestamps();
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->index('id');
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('CASCADE');
            $table->foreign('parent_id')->references('id')->on('comments')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('comments')) {
            Schema::table('comments', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->dropForeign(['post_id']);
                $table->dropForeign(['parent_id']);
            });
            Schema::dropIfExists('comments');
        }
    }
};
