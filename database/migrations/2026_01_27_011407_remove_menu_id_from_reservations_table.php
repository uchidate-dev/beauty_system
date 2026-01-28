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
        Schema::table('reservations', function (Blueprint $table) {
            // 「外部キー制約」を削除
            $table->dropForeign(['menu_id']);
            // その後、列を削除
            $table->dropColumn('menu_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            // 元に戻すときのための処理
            $table->foreignId('menu_id')->nullable()->constrained();
        });
    }
};
