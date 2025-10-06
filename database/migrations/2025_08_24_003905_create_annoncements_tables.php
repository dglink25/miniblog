<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('content_html')->nullable(); // éditeur riche
            $table->enum('media_type', ['none','image','video'])->default('none');
            $table->string('media_url')->nullable(); // URL image/vidéo (ou storage path)
            $table->boolean('is_published')->default(true);
            $table->boolean('is_pinned')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        Schema::create('announcement_user_dismissals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('announcement_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamp('dismissed_at')->useCurrent();
            $table->unique(['announcement_id','user_id']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('announcement_user_dismissals');
        Schema::dropIfExists('announcements');
    }
};