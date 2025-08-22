<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('article_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('article_id')->constrained()->onDelete('cascade');
            $table->unsignedTinyInteger('stars'); // 1..5
            $table->timestamps();
            $table->unique(['user_id','article_id']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('article_ratings');
    }
};