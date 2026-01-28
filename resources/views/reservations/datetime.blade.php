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
            outline: 2px solid #374151 !important;
            /* charcoal gray */
            outline-offset: -2px;
            background-color: #f9fafb !important;
            transition: all 0.2s ease;
        }

        .slot-selected .status-icon {
            color: #374151 !important;
            transform: scale(1.3);
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

            {{-- ヘッダー：デザイン修正版 --}}
            <div class="mb-12 px-4 flex flex-col md:flex-row justify-between items-baseline gap-4">
                <div class="border-l-4 border-gray-900 pl-6">
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-[0.5em] mb-1">Selected Stylist</p>
                    <h1 class="staff-name text-5xl font-light text-gray-900 tracking-[0.1em] italic leading-none">
                        {{ $staff->name }}
                    </h1>
                </div>

                {{-- メニュー：視認性アップ版 --}}
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

            <form action="{{ route('reservations.store') }}" method="POST">
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
                        <button type="submit" class="w-full bg-white text-gray-900 py-4 rounded-lg text-[10px] font-bold tracking-[0.5em] hover:bg-gray-100 transition active:scale-[0.98]">
                            CONFIRM RESERVATION
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

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
                        const isBooked = booked[dStr] && booked[dStr].includes(time);
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
    </script>
</x-app-layout>