<x-app-layout>
    <x-slot name="header">
        <h2 class="font-light text-xl text-gray-800 tracking-[0.2em]">RESERVATION</h2>
    </x-slot>

    <style>
        /* 1. Hikaruを上品にするためのセリフ体（Google Fonts等がない場合も考慮した汎用セリフ） */
        .staff-name {
            font-family: "Times New Roman", "Yu Mincho", "MS Mincho", serif;
        }

        /* 2. 選択時のチャコールグレー（ベタ塗りせず、枠と薄い背景で洗練） */
        .slot-selected {
            outline: none !important;
            background-color: #374151 !important;
            color: white !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .slot-selected .status-icon {
            color: white !important;
            transform: scale(1.1);
        }

        /* ホバー時の「○」の動きを優雅に */
        .slot-btn:hover .status-icon {
            color: #374151;
            transform: scale(1.2);
        }

        /* 3. HPB風の定休日デザイン（斜線） */
        .is-holiday {
            background-color: #f3f4f6 !important;
            background-image: repeating-linear-gradient(45deg, transparent, transparent 5px, rgba(0, 0, 0, 0.03) 5px, rgba(0, 0, 0, 0.03) 10px);
            color: #d1d5db !important;
        }

        /* 4. 過去・満席の表現（ださくない×） */
        .is-unavailable {
            color: #e5e7eb !important;
            background-color: #ffffff;
            cursor: not-allowed;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>

    <div class="py-12 bg-[#fafafa] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-8 px-4">
                <a href="{{ route('reservations.index') }}" class="inline-flex items-center text-[10px] tracking-[0.3em] text-gray-400 hover:text-black transition-all duration-300 group">
                    <svg class="w-4 h-4 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    BACK TO MENU
                </a>
            </div>
            {{-- ヘッダー：デザイン修正版 --}}
            <div class="mb-12 px-4 flex flex-col md:flex-row justify-between items-baseline gap-4">
                <div class="border-l-4 border-gray-900 pl-6">
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-[0.5em] mb-1">Selected Stylist</p>
                    <h1 class="staff-name text-4xl font-light text-gray-900 tracking-[0.15em] leading-none">
                        {{ $staff->name }}
                    </h1>
                </div>

                {{-- メニュー --}}
                <div class="text-left md:text-right">
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-[0.5em] mb-2">Service Menu</p>
                    <div class="flex flex-wrap md:justify-end gap-2">
                        @foreach($menus as $menu)
                        <span class="text-[11px] text-gray-700 font-medium border border-gray-300 px-4 py-1.5 bg-white rounded-sm shadow-sm">
                            {{ $menu->name }}
                        </span>
                        @endforeach
                    </div>
                </div>
            </div>

            <form action="{{ route('reservations.store') }}" method="POST" id="reservation-form">
                @csrf
                <input type="hidden" name="staff_id" value="{{ $staff->id }}">
                @foreach($menus as $menu)
                <input type="hidden" name="menu_ids[]" value="{{ $menu->id }}">
                @endforeach
                <input type="hidden" name="reservation_date" id="hidden-date" required>
                <input type="hidden" name="reservation_time" id="hidden-time" required>

                {{-- カレンダー操作 --}}
                <div class="bg-white border-t border-x border-gray-100 rounded-t-3xl p-6 flex justify-between items-center shadow-sm">
                    <button type="button" id="prev-week" onclick="changeWeek(-7)" class="text-[10px] tracking-widest text-gray-400 hover:text-black transition disabled:invisible">
                        &lt; PREVIOUS WEEK
                    </button>
                    <span id="current-month" class="text-sm font-medium tracking-[0.4em] text-gray-800"></span>
                    <button type="button" onclick="changeWeek(7)" class="text-[10px] tracking-widest text-gray-400 hover:text-black transition">
                        NEXT WEEK &gt;
                    </button>
                </div>

                {{-- カレンダー本体 --}}
                <div class="bg-white border border-gray-100 shadow-2xl overflow-hidden rounded-b-3xl">
                    <div class="overflow-x-auto no-scrollbar">
                        <table class="w-full border-collapse text-center table-fixed min-w-[800px]">
                            <thead>
                                <tr id="calendar-header" class="bg-gray-50/50 border-b border-gray-100 text-[10px] text-gray-400">
                                    {{-- JS描画 --}}
                                </tr>
                            </thead>
                            <tbody id="calendar-body" class="divide-y divide-gray-50">
                                {{-- JS描画 --}}
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- 黒いサマリーバー：洗練されたアラート風 --}}
                <div id="selection-summary" class="fixed bottom-10 left-1/2 transform -translate-x-1/2 z-50 w-[95%] max-w-lg hidden opacity-0 translate-y-10 transition-all duration-500">
                    <div class="bg-gray-900/95 backdrop-blur-sm text-white p-6 rounded-2xl shadow-[0_25px_50px_-12px_rgba(0,0,0,0.5)] flex flex-col gap-5">
                        <div class="flex justify-around items-center">
                            <div class="text-center">
                                <p class="text-[8px] text-gray-500 tracking-[0.3em] uppercase mb-1">Reservation Date</p>
                                <p id="display-date" class="text-lg font-light tracking-tight"></p>
                            </div>
                            <div class="h-6 w-[1px] bg-gray-700"></div>
                            <div class="text-center">
                                <p class="text-[8px] text-gray-500 tracking-[0.3em] uppercase mb-1">Starting Time</p>
                                <p id="display-time" class="text-lg font-light tracking-[0.2em]"></p>
                            </div>
                        </div>
                        <button type="button" onclick="openModal()" class="w-full bg-white text-gray-900 py-4 rounded-lg text-[10px] font-bold tracking-[0.5em] hover:bg-gray-100 transition active:scale-[0.98]">
                            CONFIRM RESERVATION
                        </button>
                    </div>
                </div>
                {{-- 1. 最終確認モーダル (隠し要素) --}}
                <div id="confirm-modal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
                    {{-- 背景のぼかし --}}
                    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeModal()"></div>

                    {{-- モーダル本体 --}}
                    <div class="relative bg-white w-full max-w-md p-8 rounded-3xl shadow-2xl transform scale-95 opacity-0 transition-all duration-300" id="modal-content">
                        <h3 class="text-[10px] font-bold tracking-[0.5em] text-gray-400 uppercase mb-8 text-center border-b pb-4">Confirm Your Booking</h3>

                        <div class="space-y-6 mb-10">
                            <div class="flex justify-between items-baseline">
                                <span class="text-[9px] text-gray-400 tracking-widest uppercase">Stylist</span>
                                <span class="staff-name text-xl text-gray-900">{{ $staff->name }}</span>
                            </div>
                            <div class="flex justify-between items-baseline">
                                <span class="text-[9px] text-gray-400 tracking-widest uppercase">Menu</span>
                                <div class="text-right">
                                    @foreach($menus as $menu)
                                    <p class="text-xs text-gray-800 font-medium">{{ $menu->name }}</p>
                                    @endforeach
                                </div>
                            </div>
                            <div class="flex justify-between items-baseline">
                                <span class="text-[9px] text-gray-400 tracking-widest uppercase">Date / Time</span>
                                <span id="modal-display-dt" class="text-sm font-bold text-gray-900 tracking-tighter"></span>
                            </div>
                            <div class="pt-4 border-t border-dashed flex justify-between items-baseline">
                                <span class="text-[9px] text-gray-900 font-bold tracking-widest uppercase">Total Amount</span>
                                <span class="text-xl font-light text-gray-900">¥{{ number_format($menus->sum('price')) }}</span>
                            </div>
                        </div>

                        <div class="flex flex-col gap-3">
                            <button type="submit" form="reservation-form" class="w-full bg-gray-900 text-white py-4 rounded-xl flex flex-col items-center justify-center hover:bg-black transition shadow-lg">
                                <span class="text-[10px] font-bold tracking-[0.3em]">BOOK NOW</span>
                                <span class="text-[8px] opacity-60 tracking-[0.1em] mt-0.5">予約を確定する</span>
                            </button> <button type="button" onclick="closeModal()" class="w-full text-[9px] text-gray-400 tracking-widest py-2 hover:text-gray-900 transition uppercase">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <script>
                let currentStartDate = new Date();
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                const staffId = "{{ $staff->id }}";

                document.addEventListener('DOMContentLoaded', () => loadAvailability(currentStartDate));

                function changeWeek(days) {
                    const next = new Date(currentStartDate);
                    next.setDate(next.getDate() + days);
                    if (next < today && days < 0) return;
                    currentStartDate = next;
                    loadAvailability(currentStartDate);
                }

                async function loadAvailability(baseDate) {
                    document.getElementById('prev-week').style.visibility = (baseDate <= today) ? 'hidden' : 'visible';
                    const dateStr = baseDate.toISOString().split('T')[0];
                    const response = await fetch(`/api/reservations/check-week?staff_id=${staffId}&start_date=${dateStr}`);
                    const data = await response.json();
                    renderCalendar(baseDate, data);
                }

                function renderCalendar(startDate, data) {
                    const {
                        booked,
                        holidays
                    } = data;
                    const header = document.getElementById('calendar-header');
                    const body = document.getElementById('calendar-body');
                    const monthDisplay = document.getElementById('current-month');

                    monthDisplay.innerText = `${startDate.getFullYear()}年 ${startDate.getMonth() + 1}月`;

                    let headerHtml = '<th class="p-4 w-20 border-r border-gray-50 font-bold">TIME</th>';
                    const dateArray = [];
                    const dayNames = ['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'];

                    for (let i = 0; i < 7; i++) {
                        const d = new Date(startDate);
                        d.setDate(d.getDate() + i);
                        const dStr = d.toISOString().split('T')[0];
                        dateArray.push(dStr);
                        const isToday = today.toISOString().split('T')[0] === dStr;
                        const dayColor = d.getDay() === 0 ? 'text-red-400' : (d.getDay() === 6 ? 'text-blue-400' : 'text-gray-400');

                        headerHtml += `
                    <th class="p-5 border-r border-gray-50 ${isToday ? 'bg-red-50/10' : ''}">
                        <div class="${dayColor} mb-1">${dayNames[d.getDay()]}</div>
                        <div class="text-lg font-light ${isToday ? 'text-red-600 font-medium' : 'text-gray-900'}">${d.getDate()}</div>
                    </th>`;
                    }
                    header.innerHTML = headerHtml;

                    let bodyHtml = '';
                    for (let h = 10; h <= 18; h++) {
                        ['00', '30'].forEach(m => {
                            const time = `${h.toString().padStart(2, '0')}:${m}`;
                            bodyHtml += `<tr>`;
                            bodyHtml += `<td class="p-3 border-r border-gray-50 text-[10px] text-gray-400 bg-gray-50/20">${time}</td>`;

                            dateArray.forEach(dStr => {
                                const cellDate = new Date(dStr + ' ' + time);
                                const isBooked = booked && booked[dStr] && booked[dStr].includes(time);
                                const isHoliday = holidays.includes(new Date(dStr).getDay());
                                const isPast = cellDate < new Date();

                                if (isHoliday) {
                                    bodyHtml += `<td class="is-holiday border-r border-gray-50 text-[10px]">休</td>`;
                                } else if (isBooked || isPast) {
                                    bodyHtml += `<td class="is-unavailable border-r border-gray-50 text-xs">×</td>`;
                                } else {
                                    bodyHtml += `
                                <td class="p-0 border-r border-gray-50">
                                    <button type="button" onclick="selectSlot('${dStr}', '${time}', this)" 
                                        class="slot-btn w-full h-full py-5 flex items-center justify-center transition hover:bg-red-50 group/btn">
                                        <span class="status-icon text-red-400/60 text-lg group-hover/btn:scale-125 transition">○</span>
                                    </button>
                                </td>`;
                                }
                            });
                            bodyHtml += `</tr>`;
                        });
                    }
                    body.innerHTML = bodyHtml;
                }

                function selectSlot(date, time, btn) {
                    document.querySelectorAll('.slot-btn').forEach(b => b.classList.remove('slot-selected'));
                    btn.classList.add('slot-selected');
                    document.getElementById('hidden-date').value = date;
                    document.getElementById('hidden-time').value = time;
                    document.getElementById('display-date').innerText = date.replace(/-/g, '.');
                    document.getElementById('display-time').innerText = time;
                    const summary = document.getElementById('selection-summary');
                    summary.classList.remove('hidden');
                    setTimeout(() => summary.classList.add('opacity-100', 'translate-y-0'), 10);
                }

                // --- モーダル制御用の関数 ---
                function openModal() {
                    const date = document.getElementById('hidden-date').value;
                    const time = document.getElementById('hidden-time').value;

                    // 日付と時間が選ばれていなければ何もしない
                    if (!date || !time) return;

                    // モーダル内の表示を更新
                    document.getElementById('modal-display-dt').innerText = `${date.replace(/-/g, '/')} ${time}`;

                    const modal = document.getElementById('confirm-modal');
                    const content = document.getElementById('modal-content');

                    // 表示開始
                    modal.classList.remove('hidden');
                    // 少し遅らせてアニメーション（scaleとopacity)を効かせる
                    setTimeout(() => {
                        content.classList.remove('scale-95', 'opacity-0');
                        content.classList.add('scale-100', 'opacity-100');
                    }, 10);
                }

                function closeModal() {
                    const content = document.getElementById('modal-content');
                    // アニメーションで消す
                    content.classList.remove('scale-100', 'opacity-100');
                    content.classList.add('scale-95', 'opacity-0');

                    // アニメーションが終わるのを待ってから完全に隠す
                    setTimeout(() => {
                        document.getElementById('confirm-modal').classList.add('hidden');
                    }, 300);
                }
            </script>
</x-app-layout>