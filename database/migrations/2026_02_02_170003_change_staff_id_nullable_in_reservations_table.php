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
            // reservationsテーブルの staff_id を「NULL(空っぽ)」に変更
            $table->unsignedBigInteger('staff_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            // 元に戻すときは「NULL」NG
            $table->unsignedBigInteger('staff_id')->nullable(false)->change();
        });
    }
};
