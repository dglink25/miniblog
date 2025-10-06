<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('reference')->unique(); // ID paiement du provider
            $table->string('status')->default('pending'); // success, failed, cancelled
            $table->decimal('amount', 10, 2);
            $table->string('method')->nullable(); // Fedapay, Mobile Money, etc.
            $table->string('purpose'); // ex: boost_article, abonnement, etc.
            $table->json('meta')->nullable(); // infos additionnelles (article_id, etc.)
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void{
        Schema::dropIfExists('payments');
    }
};
