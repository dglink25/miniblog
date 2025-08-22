<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
     public function up(): void {
        Schema::table('articles', function (Blueprint $table) {
            $table->boolean('is_published')->default(false)->after('image_path');
            $table->timestamp('published_at')->nullable()->after('is_published');
        });
    }
    public function down(): void
    {
    Schema::table('articles', function (Blueprint $table) {
        if (Schema::hasColumn('articles', 'is_published')) {
            $table->dropColumn('is_published');
        }

        if (Schema::hasColumn('articles', 'published_at')) {
            $table->dropColumn('published_at');
        }
    });
}

};
