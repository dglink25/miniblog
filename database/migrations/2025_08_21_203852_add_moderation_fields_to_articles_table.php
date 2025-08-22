<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void{
        
    }

    /**
     * Reverse the migrations.
     */
    
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            if (Schema::hasColumn('articles','status')) $table->dropColumn('status');
            if (Schema::hasColumn('articles','rejection_reason')) $table->dropColumn('rejection_reason');
            if (Schema::hasColumn('articles','published_at')) $table->dropColumn('published_at');
        });
    }
};
