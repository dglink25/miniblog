<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('plan', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->unsignedInteger('duration_days'); // ex: 30, 90, 180, 365
            $t->unsignedInteger('price'); // en XOF ou la devise choisie
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });

        Schema::create('subscription', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->foreignId('plan_id')->constrained('plan')->cascadeOnDelete();
            $t->timestamp('starts_at')->useCurrent();
            $t->timestamp('ends_at')->nullable(); // ✅ correction
            $t->enum('status', ['pending', 'active', 'expired', 'canceled'])->default('pending');
            $t->string('payment_ref')->nullable();
            $t->unsignedInteger('paid_amount')->nullable();
            $t->string('verification_code', 12)->nullable();
            $t->timestamps();

            $t->index(['user_id', 'status']);
        });

        Schema::table('site_settings', function (Blueprint $t) {
            if (!Schema::hasColumn('site_setting', 'trial_days')) {
                $t->unsignedInteger('trial_days')->default(50);
            }
        });
    }

    public function down(): void {
        Schema::dropIfExists('subscription');
        Schema::dropIfExists('plan');
        // on ne supprime pas trial_days pour éviter les pertes
    }
};
