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
        Schema::table('articles', function (Blueprint $table) {
            // Ajouter la colonne views_count si elle n'existe pas
            if (!Schema::hasColumn('articles', 'views_count')) {
                $table->integer('views_count')->default(0)->after('content');
            }

            // Ajouter la colonne comments_count si elle n'existe pas
            if (!Schema::hasColumn('articles', 'comments_count')) {
                $table->integer('comments_count')->default(0)->after('views_count');
            }

            // Ajouter la colonne likes_count si elle n'existe pas
            if (!Schema::hasColumn('articles', 'likes_count')) {
                $table->integer('likes_count')->default(0)->after('comments_count');
            }

            // Ajouter la colonne pinned si elle n'existe pas
            if (!Schema::hasColumn('articles', 'pinned')) {
                $table->boolean('pinned')->default(false)->after('status');
            }
        });

        // Créer la table suggestions si elle n'existe pas
        if (!Schema::hasTable('suggestions')) {
            Schema::create('suggestions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('title');
                $table->text('content');
                $table->enum('type', ['feature', 'improvement', 'bug'])->default('feature');
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
                $table->text('admin_notes')->nullable();
                $table->timestamps();
            });
        }

        // Créer la table reactions si elle n'existe pas
        if (!Schema::hasTable('reactions')) {
            Schema::create('reactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->morphs('reactable'); // Permet de réagir à articles et commentaires
                $table->string('type'); // like, love, laugh, etc.
                $table->timestamps();

                $table->unique(['user_id', 'reactable_id', 'reactable_type', 'type']);
            });
        }

        // Créer la table ratings si elle n'existe pas
        if (!Schema::hasTable('ratings')) {
            Schema::create('ratings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('article_id')->constrained()->onDelete('cascade');
                $table->tinyInteger('stars')->unsigned()->between(1, 5);
                $table->text('comment')->nullable();
                $table->timestamps();

                $table->unique(['user_id', 'article_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn(['views_count', 'comments_count', 'likes_count', 'pinned']);
        });

        Schema::dropIfExists('suggestions');
        Schema::dropIfExists('reactions');
        Schema::dropIfExists('ratings');
    }
};