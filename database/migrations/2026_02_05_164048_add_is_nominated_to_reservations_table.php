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
            /**
             * staff_id の後ろに、is_nominated (指名フラグ)を追加
             * default(false) = 何も言わなければ「指名なし」として扱う
             */
            $table->boolean('is_nominated')->default(false)->after('staff_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn('is_nominated');
        });
    }
};
