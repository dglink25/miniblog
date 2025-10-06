<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Si la table n'existe pas, on la crée complètement
        if (!Schema::hasTable('suggestions')) {
            Schema::create('suggestions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('subject')->nullable();
                $table->text('message');
                $table->enum('status', ['new', 'seen', 'closed'])->default('new');
                $table->timestamps();
            });
        } 
        // Si la table existe déjà, on vérifie colonne par colonne
        else {
            Schema::table('suggestions', function (Blueprint $table) {
                if (!Schema::hasColumn('suggestions', 'user_id')) {
                    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                }
                if (!Schema::hasColumn('suggestions', 'subject')) {
                    $table->string('subject')->nullable();
                }
                if (!Schema::hasColumn('suggestions', 'message')) {
                    $table->text('message');
                }
                if (!Schema::hasColumn('suggestions', 'status')) {
                    $table->enum('status', ['new', 'seen', 'closed'])->default('new');
                }
                if (!Schema::hasColumn('suggestions', 'created_at')) {
                    $table->timestamps();
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('suggestions');
    }
};
