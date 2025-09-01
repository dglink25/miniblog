<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('plan', function (Blueprint $table) {
            if (!Schema::hasColumn('plan', 'payment_provider')) {
                $table->string('payment_provider')->default('kia')->after('price'); // kia|feda|other
            }

            if (!Schema::hasColumn('plan', 'payment_link')) {
                $table->text('payment_link')->nullable()->after('payment_provider');
            }
        });
    }

    public function down(): void {
        Schema::table('plan', function (Blueprint $table) {
            if (Schema::hasColumn('plan', 'payment_provider')) {
                $table->dropColumn('payment_provider');
            }
            if (Schema::hasColumn('plan', 'payment_link')) {
                $table->dropColumn('payment_link');
            }
        });
    }
};
