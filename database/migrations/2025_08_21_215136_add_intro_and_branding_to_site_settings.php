<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('site_settings','auto_publish')) $table->boolean('auto_publish')->default(false);
            if (!Schema::hasColumn('site_settings','company_name')) $table->string('company_name')->nullable();
            if (!Schema::hasColumn('site_settings','site_name')) $table->string('site_name')->nullable();
            if (!Schema::hasColumn('site_settings','intro_enabled')) $table->boolean('intro_enabled')->default(true);
            if (!Schema::hasColumn('site_settings','intro_html')) $table->longText('intro_html')->nullable();
        });
    }
    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
        $cols = ['auto_publish','company_name','site_name','intro_enabled','intro_html'];
        foreach ($cols as $c) if (Schema::hasColumn('site_settings',$c)) $table->dropColumn($c);
    });
}
};