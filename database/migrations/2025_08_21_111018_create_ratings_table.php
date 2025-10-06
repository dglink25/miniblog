<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade'); // 1 note par user
            $table->tinyInteger('stars'); // 1..5
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('ratings');
    }

};
