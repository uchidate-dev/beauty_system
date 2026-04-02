<x-app-layout>
    <x-slot name="header">
        <h2 class="font-light text-2xl text-gray-700 leading-tight tracking-widest">
            MY PAGE
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            {{-- 成功メッセージの表示 --}}
            @if (session('success'))
                <div class="mb-6 bg-white border-l-4 border-gray-800 p-4 shadow-sm">
                    <p class="text-sm text-gray-600 tracking-tighter">{{ session('success') }}</p>
                </div>
            @endif

            {{-- エラーメッセージの表示 --}}
            @if (session('error'))
                <div class="mb-6 bg-white border-l-4 border-red-400 p-4 shadow-sm">
                    <p class="text-sm text-red-500 tracking-tighter">{{ session('error') }}</p>
                </div>
            @endif

            {{-- メインコンテンツ：予約セクション --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 mb-8">

                {{-- ウェルカムメッセージ --}}
                <div class="text-center mb-12 pb-8 border-b border-gray-100">
                    <p class="text-[10px] tracking-[0.2em] text-gray-400 mb-2 uppercase">Welcome Back</p>
                    <h3 class="text-xl font-light text-gray-800 tracking-wider">
                        {{ Auth::user()->name }} <span class="text-sm">様、おかえりなさいませ。</span>
                    </h3>
                </div>

                <div class="flex justify-between items-end mb-8 border-b border-gray-100 pb-4">
                    <h3 class="text-base sm:text-lg font-light tracking-widest text-gray-800">RESERVATIONS / 予約一覧</h3>

                    {{-- 予約がある場合のみ、右上の小さい新規予約ボタンを表示 --}}
                    @if (!$upcomingReservations->isEmpty())
                        <a href="{{ route('reservations.index') }}"
                            class="text-[9px] sm:text-[10px] whitespace-nowrap shrink-0 border border-gray-800 px-3 py-1.5 sm:px-4 sm:py-2 hover:bg-gray-800 hover:text-white transition-all duration-300">
                            NEW BOOKING / 新規予約
                        </a>
                    @endif
                </div>

                {{-- 未来の予約 --}}
                @if ($upcomingReservations->isEmpty())
                    {{-- 予約がない時の画面 --}}
                    <div class="text-center py-16 bg-gray-50/50 border border-gray-100">
                        <p class="text-sm text-gray-500 font-light tracking-widest mb-4">現在、予定されているご予約はありません。</p>
                        <p class="text-[11px] text-gray-400 mb-10 leading-relaxed tracking-wider">
                            日常を離れ、特別な時間を過ごしませんか？<br>あなたのご来店を心よりお待ちしております。
                        </p>
                        <a href="{{ route('reservations.index') }}"
                            class="inline-block bg-gray-800 text-white text-xs tracking-[0.2em] px-10 py-4 hover:bg-gray-600 transition-all duration-300">
                            BOOK AN APPOINTMENT / 新規予約
                        </a>
                    </div>
                @else
                    {{-- 予約がある時のリスト --}}
                    <div class="space-y-6">
                        @foreach ($upcomingReservations as $reservation)
                            <div class="group border border-gray-100 p-6 hover:border-gray-300 transition-all bg-white">
                                <div class="flex justify-between items-start">
                                    <div>
                                        {{-- 日付表示（日本語・曜日付き） --}}
                                        <p class="text-xs text-gray-400 mb-1 tracking-tighter">
                                            {{ \Carbon\Carbon::parse($reservation->reservation_date)->format('Y年m月d日') }}
                                            ({{ ['日', '月', '火', '水', '木', '金', '土'][\Carbon\Carbon::parse($reservation->reservation_date)->dayOfWeek] }})
                                            {{ substr($reservation->reservation_time, 0, 5) }}
                                        </p>

                                        {{-- 指名フラグによる表示切替 --}}
                                        <p class="text-lg font-medium text-gray-800 tracking-widest">
                                            @if ($reservation->is_nominated && $reservation->staff)
                                                {{-- 指名ありならスタッフ名を表示 --}}
                                                {{ $reservation->staff->name }}
                                            @else
                                                {{-- 指名なし（またはスタッフ未定）なら「指名なし」を表示 --}}
                                                指名なし
                                            @endif
                                        </p>

                                        <div class="mt-2 flex flex-wrap gap-2">
                                            @foreach ($reservation->menus as $menu)
                                                <span
                                                    class="text-[10px] bg-gray-50 text-gray-500 px-2 py-1 rounded">{{ $menu->name }}</span>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="text-right" x-data="{ open: false }">
                                        {{-- Status表示 --}}
                                        <span
                                            class="text-[10px] tracking-widest uppercase text-gray-400 italic">Confirmed
                                            / 予約確定</span>

                                        {{-- 金額表示 --}}
                                        <p class="text-lg font-light text-gray-900 mt-1">
                                            ¥{{ number_format($reservation->menus->sum('price')) }}
                                        </p>

                                        <p class="text-[10px] text-gray-500 mt-1 mb-4">ご来店をお待ちしております</p>

                                        {{-- キャンセル期限の判定ロジック --}}
                                        @php
                                            $deadline = \Carbon\Carbon::parse($reservation->reservation_date)
                                                ->subDay()
                                                ->endOfDay();
                                            $canCancel = now()->lessThanOrEqualTo($deadline);
                                        @endphp

                                        @if ($canCancel)
                                            {{-- 期限内：キャンセルボタンとモーダルを表示 --}}
                                            <button @click="open = true" type="button"
                                                class="text-[10px] text-red-300 hover:text-red-500 transition-colors tracking-widest border-b border-red-100">
                                                CANCEL / キャンセル
                                            </button>

                                            {{-- モーダル本体 --}}
                                            <template x-teleport="body">
                                                <div x-show="open"
                                                    x-transition:enter="transition ease-out duration-300"
                                                    x-transition:enter-start="opacity-0"
                                                    x-transition:enter-end="opacity-100"
                                                    x-transition:leave="transition ease-in duration-200"
                                                    x-transition:leave-start="opacity-100"
                                                    x-transition:leave-end="opacity-0"
                                                    class="fixed inset-0 z-[100] flex items-center justify-center bg-black/40 backdrop-blur-sm px-4"
                                                    x-cloak>

                                                    {{-- 白いカード部分 --}}
                                                    <div class="bg-white p-10 max-w-sm w-full shadow-2xl text-center border border-gray-100"
                                                        @click.away="open = false">
                                                        <h4
                                                            class="text-sm font-light tracking-[0.2em] text-gray-800 mb-6">
                                                            CONFIRM CANCEL</h4>
                                                        <p
                                                            class="text-[11px] text-gray-500 mb-10 leading-relaxed tracking-wider">
                                                            予約をキャンセルしてもよろしいですか？<br>この操作は取り消せません。
                                                        </p>

                                                        <div class="flex justify-center items-center gap-10">
                                                            {{-- 戻るボタン --}}
                                                            <button @click="open = false"
                                                                class="text-[10px] tracking-[0.2em] text-gray-400 hover:text-gray-800 transition-colors uppercase border-b border-transparent hover:border-gray-800">
                                                                Back / 戻る
                                                            </button>

                                                            {{-- 確定ボタン --}}
                                                            <form
                                                                action="{{ route('reservations.destroy', $reservation) }}"
                                                                method="POST">
                                                                @csrf @method('DELETE')
                                                                <button type="submit"
                                                                    class="text-[10px] tracking-[0.2em] text-red-400 hover:text-red-600 border border-red-100 px-6 py-2.5 transition-all bg-white hover:bg-red-50">
                                                                    CONFIRM / 確定
                                                                </button>
                                                            </form>

                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        @else
                                            {{-- 期限切れ：店舗への案内メッセージを表示 --}}
                                            <div class="mt-2 border-t border-gray-100 pt-3">
                                                <p class="text-[10px] text-gray-400 tracking-widest leading-relaxed">
                                                    ※キャンセル期限を過ぎています<br>変更・キャンセルはお電話にて承ります
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- 履歴セクション --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 mb-8">
                <div class="mb-8 border-b border-gray-100 pb-4">
                    <h3 class="text-lg font-light tracking-widest text-gray-800">HISTORY / ご来店履歴</h3>
                </div>

                @if ($pastReservations->isEmpty())
                    <p class="text-gray-400 text-sm text-center py-8">過去のご来店履歴はありません。</p>
                @else
                    <div class="grid grid-cols-1 gap-4 opacity-70">
                        @foreach ($pastReservations as $reservation)
                            <div class="p-5 border-l-2 border-gray-200 bg-gray-50/50 flex justify-between items-center">
                                <div>
                                    <p class="text-[10px] text-gray-400">
                                        {{ \Carbon\Carbon::parse($reservation->reservation_date)->format('Y.m.d') }}
                                    </p>
                                    <p class="text-sm font-medium text-gray-600 tracking-wider">
                                        {{ $reservation->staff->name ?? '指名なし' }}
                                    </p>
                                    <p class="text-[10px] text-gray-400 mt-1">
                                        @foreach ($reservation->menus as $menu)
                                            {{ $menu->name }}{{ !$loop->last ? ', ' : '' }}
                                        @endforeach
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-500 font-light italic">
                                        ¥{{ number_format($reservation->menus->sum('price')) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- お知らせセクション --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                <div class="mb-6 border-b border-gray-100 pb-4">
                    <h3 class="text-lg font-light tracking-widest text-gray-800">INFORMATION / お知らせ</h3>
                </div>
                <div class="space-y-4">
                    <div class="flex items-start gap-4 sm:gap-6 pb-4 border-b border-gray-50">
                        <span
                            class="text-[9px] sm:text-[10px] text-gray-400 tracking-widest pt-1 shrink-0 whitespace-nowrap">{{ now()->subWeeks(2)->format('Y.m.d') }}</span>
                        <p class="text-xs sm:text-sm text-gray-600 font-light tracking-wider">
                            季節の新作トリートメント「モイスチャー・ケア」を導入しました。</p>
                    </div>
                    <div class="flex items-start gap-4 sm:gap-6 pb-4 border-b border-gray-50">
                        <span
                            class="text-[9px] sm:text-[10px] text-gray-400 tracking-widest pt-1 shrink-0 whitespace-nowrap">{{ now()->subMonth()->format('Y.m.d') }}</span>
                        <p class="text-xs sm:text-sm text-gray-600 font-light tracking-wider">スタイリストの来月のお休みについて</p>
                    </div>
                    <div class="flex items-start gap-4 sm:gap-6 pb-2">
                        <span
                            class="text-[9px] sm:text-[10px] text-gray-400 tracking-widest pt-1 shrink-0 whitespace-nowrap">{{ now()->startOfYear()->format('Y.m.d') }}</span>
                        <p class="text-xs sm:text-sm text-gray-600 font-light tracking-wider">
                            ご予約のキャンセルは、前日の23:59までマイページより可能です。</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
