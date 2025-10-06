<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (Schema::hasTable('plans')) {
            Schema::table('plans', function (Blueprint $table) {
                if (!Schema::hasColumn('plans', 'payment_provider')) {
                    $table->string('payment_provider')->default('kia')->after('price'); // kia|feda|other
                }
                if (!Schema::hasColumn('plans', 'payment_link')) {
                    $table->text('payment_link')->nullable()->after('payment_provider'); // lien KIApay
                }
                if (!Schema::hasColumn('plans', 'is_active')) {
                    $table->boolean('is_active')->default(true)->after('payment_link');
                }
            });
        } else {
            echo "⚠️ Table 'plans' introuvable — migration ignorée.\n";
        }
    }

    public function down(): void {
        if (Schema::hasTable('plans')) {
            Schema::table('plans', function (Blueprint $table) {
                $table->dropColumn(['payment_provider', 'payment_link', 'is_active']);
            });
        }
    }
};

