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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            // 誰が(User)、誰に(Staff)、何を(Menu)を紐付けるID
            $table->foreignId('user_id')->constrained();
            $table->foreignId('staff_id')->constrained('staffs');
            $table->foreignId('menu_id')->constrained('menus');
            // 予約日 
            $table->date('reservation_date');
            // 予約時間
            $table->time('reservation_time');
            // ステータス管理（デフォルトは「予約中(pending)」）
            $table->string('status')->default('pending');
            // pending, visited(来店済), cancelled(キャンセル), no_show(無断)

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
