<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->default('DGLINK');
            $table->string('site_name')->default('DGLINK — Pub');
            $table->boolean('auto_publish')->default(false);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('site_settings');
    }
};
