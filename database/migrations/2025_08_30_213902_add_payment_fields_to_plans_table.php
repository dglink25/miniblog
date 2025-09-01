<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    public function up(): void{
        Schema::table('plans', function (Blueprint $table) {
            $table->string('payment_provider')->default('kia')->after('price'); // kia|feda|other
            $table->text('payment_link')->nullable()->after('payment_provider'); // lien KIApay
            $table->boolean('is_active')->default(true)->after('payment_link');
        });
    }

    public function down(): void{
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn(['payment_provider','payment_link','is_active']);
        });
    }
};
