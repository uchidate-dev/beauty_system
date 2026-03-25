<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Menu;
use Carbon\Carbon;
use Symfony\Component\Console\Command\CompleteCommand;

class ReservationSeeder extends Seeder
{
    public function run(): void
    {
        // 必須データの取得
        $testUser = User::where('email', 'test@test')->first();
        $dummies = User::where('email', 'like', 'dummy%')->get();

        $menuCut = Menu::find(1) ?? Menu::first();
        $menuColor = Menu::find(2) ?? Menu::skip(1)->first() ?? $menuCut;

        if (!$testUser || $dummies->isEmpty() || !$menuCut) return;

        $today = now()->format('Y-m-d');
        $tomorrow = now()->addDay()->format('Y-m-d');
        $past = now()->subDays(5)->format('Y-m-d');
        $future = now()->addDays(3)->format('Y-m-d');

        // ==========================================
        // デモユーザー用のパーソナルデータ
        // ==========================================
        // 過去の履歴 (来店済み)
        $this->createRes($testUser->id, 2, $past, '14:00', $menuColor, true, 'completed');
        // 未来の予約(マイページ確認用)
        $this->createRes($testUser->id, 1, $future, '11:00', $menuCut, true, 'pending');

        // ==========================================
        //  【今日】の大繁盛タイムライン（管理画面デモ用）
        // ==========================================
        // Hikaru (ID:1) が今日休みじゃなければ予約を入れる
        if (!$this->isHoliday(1, $today)) {
            $this->createRes($dummies[0]->id, 1, $today, '10:00', $menuCut, true);
            $this->createRes($dummies[1]->id, 1, $today, '11:30', $menuColor, true);
            $this->createRes($dummies[2]->id, 1, $today, '15:00', $menuCut, true);
        }

        // Yuki (ID:2) が今日休みじゃなければ予約を入れる
        if (!$this->isHoliday(2, $today)) {
            $this->createRes($dummies[3]->id, 2, $today, '10:30', $menuColor, true);
            $this->createRes($dummies[4]->id, 2, $today, '13:00', $menuCut, true);
            $this->createRes($dummies[5]->id, 2, $today, '16:30', $menuColor, true);
        }

        // Ken (ID:3) が今日休みじゃなければ予約を入れる
        if (!$this->isHoliday(3, $today)) {
            $this->createRes($dummies[6]->id, 3, $today, '12:00', $menuCut, true);
            $this->createRes($dummies[7]->id, 3, $today, '14:00', $menuColor, true);
        }

        //  指名なし（staff_id = null）は店舗の予約枠として常に入れる
        $this->createRes($dummies[8]->id, null, $today, '14:00', $menuCut, false);
        $this->createRes($dummies[9]->id, null, $today, '18:00', $menuColor, false);

        // ==========================================
        //  【明日】の大繁盛タイムライン（明日が休みかどうかも判定）
        // ==========================================
        if (!$this->isHoliday(1, $tomorrow)) {
            $this->createRes($dummies[1]->id, 1, $tomorrow, '10:00', $menuColor, true);
            $this->createRes($dummies[9]->id, 1, $tomorrow, '16:00', $menuCut, true);
        }
        if (!$this->isHoliday(2, $tomorrow)) {
            $this->createRes($dummies[3]->id, 2, $tomorrow, '12:00', $menuCut, true);
        }
        if (!$this->isHoliday(3, $tomorrow)) {
            $this->createRes($dummies[5]->id, 3, $tomorrow, '11:30', $menuCut, true);
        }
        $this->createRes($dummies[7]->id, null, $tomorrow, '15:00', $menuColor, false);
    }

    /**
     *  定休日を判定する賢いメソッド
     */
    private function isHoliday($staffId, $date)
    {
        if ($staffId === null) return false; // 指名なし枠は休み判定しない

        $dayOfWeek = date('w', strtotime($date));
        $holidayMap = [
            1 => [2, 3], // Hikaru (火・水)
            2 => [2, 1], // Yuki (月・火)
            3 => [2, 4], // Ken (火・木)
        ];

        return in_array($dayOfWeek, $holidayMap[$staffId] ?? []);
    }

    /**
     * コマ自動生成メソッド
     */
    private function createRes($userId, $staffId, $date, $startTime, $menu, $isNominated, $status = 'pending')
    {
        $duration = $menu->duration ?? 60;
        $frames = ceil($duration / 30);
        $startUnix = strtotime($startTime);
        $now = now();

        for ($i = 0; $i < $frames; $i++) {
            $res = Reservation::create([
                'user_id' => $userId,
                'staff_id' => $staffId,
                'reservation_date' => $date,
                'reservation_time' => date('H:i', $startUnix + ($i * 1800)),
                'status' => $status,
                'is_nominated' => $isNominated,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            if ($i === 0) {
                $res->menus()->attach($menu->id);
            }
        }
    }
}
