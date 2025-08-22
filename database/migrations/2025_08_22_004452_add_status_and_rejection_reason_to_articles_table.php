<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('articles', function (Blueprint $table) {
        if (!Schema::hasColumn('articles', 'rejection_reason')) {
            $table->text('rejection_reason')->nullable();
        }
        // ne pas recréer "status" puisqu’il existe déjà
    });

}

public function down()
{
    Schema::table('articles', function (Blueprint $table) {
        $table->dropColumn(['status', 'rejection_reason']);
    });
}

};
