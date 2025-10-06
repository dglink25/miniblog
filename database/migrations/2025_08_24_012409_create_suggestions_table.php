<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('suggestions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('subject')->nullable();
            $table->text('message');
            $table->enum('status',['new','seen','closed'])->default('new');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('suggestions');
    }
};
