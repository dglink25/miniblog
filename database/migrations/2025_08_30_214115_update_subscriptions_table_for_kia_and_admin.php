<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('subscription', function (Blueprint $table) {

            // Status
            if (!Schema::hasColumn('subscription','status')) {
                $table->string('status')->default('pending'); // pending|active|expired|cancelled
            } else {
                $table->string('status')->default('pending')->change();
            }

            // Source
            if (!Schema::hasColumn('subscription','source')) {
                $table->string('source')->default('kia')->after('status'); // kia|feda|admin
            }

            // Verification code
            if (!Schema::hasColumn('subscription','verification_code')) {
                $table->string('verification_code')->nullable()->after('source');
            }

            // Paid amount
            if (!Schema::hasColumn('subscription','paid_amount')) {
                $table->integer('paid_amount')->nullable()->after('verification_code');
            }

            // Paid at
            if (!Schema::hasColumn('subscription','paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('paid_amount');
            }

            // Payment link
            if (!Schema::hasColumn('subscription','payment_link')) {
                $table->text('payment_link')->nullable()->after('paid_at');
            }

            // Metadata
            if (!Schema::hasColumn('subscription','metadata')) {
                $table->json('metadata')->nullable()->after('payment_link');
            }

            // Starts_at / ends_at nullable
            if (Schema::hasColumn('subscription','starts_at')) {
                $table->timestamp('starts_at')->nullable()->change();
            }
            if (Schema::hasColumn('subscription','ends_at')) {
                $table->timestamp('ends_at')->nullable()->change();
            }

            // Indexes
            $table->index(['status','source'], 'subscription_status_source_index');
            if (Schema::hasColumn('subscription','user_id') && Schema::hasColumn('subscription','plan_id')) {
                $table->index(['user_id','plan_id'], 'subscription_user_id_plan_id_index');
            }
            if (Schema::hasColumn('subscription','starts_at') && Schema::hasColumn('subscription','ends_at')) {
                $table->index(['starts_at','ends_at'], 'subscription_starts_at_ends_at_index');
            }
        });
    }

    public function down(): void {
        Schema::table('subscription', function (Blueprint $table) {
            // Drop indexes s'ils existent
            $table->dropIndex('subscription_status_source_index');
            $table->dropIndex('subscription_user_id_plan_id_index');
            $table->dropIndex('subscription_starts_at_ends_at_index');

            // Drop columns si elles existent
            if (Schema::hasColumn('subscription','source')) $table->dropColumn('source');
            if (Schema::hasColumn('subscription','verification_code')) $table->dropColumn('verification_code');
            if (Schema::hasColumn('subscription','paid_amount')) $table->dropColumn('paid_amount');
            if (Schema::hasColumn('subscription','paid_at')) $table->dropColumn('paid_at');
            if (Schema::hasColumn('subscription','payment_link')) $table->dropColumn('payment_link');
            if (Schema::hasColumn('subscription','metadata')) $table->dropColumn('metadata');
            // on ne revient pas sur status/starts_at/ends_at par simplicité
        });
    }
};
