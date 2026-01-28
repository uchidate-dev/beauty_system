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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                <div class="flex justify-between items-end mb-8 border-b border-gray-100 pb-4">
                    <h3 class="text-lg font-light tracking-widest text-gray-800">RESERVATIONS / 予約一覧</h3>
                    <a href="{{ route('reservations.index') }}" class="text-[10px] border border-gray-800 px-4 py-2 hover:bg-gray-800 hover:text-white transition-all duration-300">
                        NEW BOOKING / 新規予約
                    </a>
                </div>

                {{-- 未来の予約session --}}
                @if($upcomingReservations->isEmpty())
                <p class="text-gray-400 text-sm text-center py-8">現在、予定されている予約はありません。</p>
                @else
                <div class="space-y-6 mb-12">
                    @foreach($upcomingReservations as $reservation)
                    <div class="group border border-gray-100 p-6 hover:border-gray-300 transition-all bg-white">
                        <div class="flex justify-between items-start">
                            <div>
                                {{-- 日付表示（日本語・曜日付き） --}}
                                <p class="text-xs text-gray-400 mb-1 tracking-tighter">
                                    {{ \Carbon\Carbon::parse($reservation->reservation_date)->format('Y年m月d日') }}
                                    ({{ ['日', '月', '火', '水', '木', '金', '土'][\Carbon\Carbon::parse($reservation->reservation_date)->dayOfWeek] }})
                                    {{ substr($reservation->reservation_time, 0, 5) }}
                                </p>
                                <p class="text-lg font-medium text-gray-800 tracking-widest">
                                    {{ $reservation->staff->name }}
                                </p>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    @foreach($reservation->menus as $menu)
                                    <span class="text-[10px] bg-gray-50 text-gray-500 px-2 py-1 rounded">{{ $menu->name }}</span>
                                    @endforeach
                                </div>
                            </div>

                            <div class="text-right" x-data="{ open: false }">
                                {{-- Status表示 --}}
                                <span class="text-[10px] tracking-widest uppercase text-gray-400 italic">Confirmed / 予約確定</span>

                                {{-- 金額表示 --}}
                                <p class="text-lg font-light text-gray-900 mt-1">
                                    ¥{{ number_format($reservation->menus->sum('price')) }}
                                </p>

                                <p class="text-[10px] text-gray-500 mt-1 mb-4">ご来店をお待ちしております</p>

                                {{-- キャンセルボタン：クリックで open を true に --}}
                                <button @click="open = true" type="button" class="text-[10px] text-red-300 hover:text-red-500 transition-colors tracking-widest border-b border-red-100">
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
                                        <div class="bg-white p-10 max-w-sm w-full shadow-2xl text-center border border-gray-100" @click.away="open = false">
                                            <h4 class="text-sm font-light tracking-[0.2em] text-gray-800 mb-6">CONFIRM CANCEL</h4>
                                            <p class="text-[11px] text-gray-500 mb-10 leading-relaxed tracking-wider">
                                                予約をキャンセルしてもよろしいですか？<br>この操作は取り消せません。
                                            </p>

                                            <div class="flex justify-center items-center gap-10">
                                                {{-- 戻るボタン --}}
                                                <button @click="open = false" class="text-[10px] tracking-[0.2em] text-gray-400 hover:text-gray-800 transition-colors uppercase border-b border-transparent hover:border-gray-800">
                                                    Back / 戻る
                                                </button>

                                                {{-- 確定ボタン --}}
                                                <form action="{{ route('reservations.destroy', $reservation) }}" method="POST">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-[10px] tracking-[0.2em] text-red-400 hover:text-red-600 border border-red-100 px-6 py-2.5 transition-all bg-white hover:bg-red-50">
                                                        CONFIRM / 確定
                                                    </button>
                                                </form>

                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif

                {{-- 過去の履歴セクション --}}
                <div class="mt-16 mb-8 border-b border-gray-100 pb-4">
                    <h3 class="text-lg font-light tracking-widest text-gray-800">HISTORY / ご来店履歴</h3>
                </div>

                @if($pastReservations->isEmpty())
                <p class="text-gray-400 text-sm text-center py-8">過去のご来店履歴はありません。</p>
                @else
                <div class="grid grid-cols-1 gap-4 opacity-70">
                    @foreach($pastReservations as $reservation)
                    <div class="p-5 border-l-2 border-gray-200 bg-gray-50/50 flex justify-between items-center">
                        <div>
                            <p class="text-[10px] text-gray-400">
                                {{ \Carbon\Carbon::parse($reservation->reservation_date)->format('Y.m.d') }}
                            </p>
                            <p class="text-sm font-medium text-gray-600 tracking-wider">{{ $reservation->staff->name }}</p>
                            <p class="text-[10px] text-gray-400 mt-1">
                                @foreach($reservation->menus as $menu) {{ $menu->name }}{{ !$loop->last ? ', ' : '' }} @endforeach
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500 font-light italic">¥{{ number_format($reservation->menus->sum('price')) }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>