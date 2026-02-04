<x-app-layout>
    <x-slot name="header">
        <h2 class="font-light text-xl text-gray-800 tracking-[0.2em] uppercase">
            Admin Dashboard <span class="text-xs opacity-50">/ 管理者画面</span>
        </h2>
    </x-slot>

    <div class="py-12 bg-[#faf9f6]">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
            <div id="success-message" class="mb-6 p-4 bg-white border-l-4 border-black shadow-sm flex items-center justify-between animate-fade-in-down">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <p class="text-[10px] tracking-[0.2em] uppercase font-medium text-gray-800">
                        {{ session('success') }}
                    </p>
                </div>
                <button onclick="document.getElementById('success-message').remove()" class="text-gray-400 hover:text-black transition">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"></path>
                    </svg>
                </button>
            </div>
            @endif

            {{-- 日付選択エリア --}}
            <div class="mb-8 flex items-center justify-between bg-white p-6 shadow-sm border-b border-gray-100">
                <form action="{{ route('admin.dashboard') }}" method="GET" class="flex items-center gap-4">
                    <input type="date" name="date" value="{{ $selectedDate }}"
                        class="border-gray-200 focus:border-black focus:ring-0 text-sm py-2">
                    <button type="submit" class="bg-black text-white px-8 py-2 text-[10px] tracking-[0.2em] uppercase hover:bg-gray-800 transition duration-300">
                        Search / 表示切替
                    </button>
                </form>

                <div class="text-right flex items-end gap-8">
                    <div>
                        <p class="text-[10px] text-gray-400 uppercase tracking-widest mb-1">Today's Revenue</p>
                        <h3 class="text-lg font-medium tracking-tighter text-gray-800">
                            <span class="text-xs mr-1">¥</span>{{ number_format($todayTotal) }}
                        </h3>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 uppercase tracking-widest mb-1">Selected Date</p>
                        <h3 class="text-lg font-light tracking-[0.1em] text-gray-800">
                            {{ \Carbon\Carbon::parse($selectedDate)->format('Y.m.d') }}
                        </h3>
                    </div>
                </div>
            </div>

            {{-- タイムライン表エリア --}}
            <div class="bg-white shadow-sm border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-50/50 border-b border-gray-100">
                                <th class="p-4 text-[10px] text-gray-400 w-24 font-medium uppercase tracking-widest border-r border-gray-100">Time</th>
                                @foreach($staffs as $staff)
                                <th class="p-4 text-[11px] tracking-[0.2em] font-medium border-r border-gray-100 uppercase
                                    {{ $staff->id == 0 || $staff->name == '指名なし' ? 'text-red-400 bg-red-50/20 font-bold' : '' }}
                                    {{-- 休みの場合のデザイン(背景グレー＆文字薄く) --}}
                                    {{  $staff->is_holiday ? 'bg-gray-200 text-gray-400' : 'text-gray-600' }}">
                                    {{ $staff->name }}
                                    {{-- 休みならバッジを表示 --}}
                                    @if($staff->is_holiday)
                                    <span class="block text-[8px] bg-gray-500 text-white rounded px-1 py-0.5 mt-1 w-fit mx-auto">HOLIDAY</span>
                                    @endif
                                </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($timeSlots as $slot)
                            <tr class="border-b border-gray-50 last:border-0 hover:bg-gray-50/30">
                                <td class="p-3 text-center text-[11px] text-gray-400 bg-gray-50/20 border-r border-gray-100">
                                    {{ $slot }}
                                </td>

                                @foreach($staffs as $staff)
                                <td class="p-1 border-r border-gray-100 h-16 min-w-[140px] relative group align-top
                                    {{ $staff->is_holiday ? 'bg-gray-100/50' : '' }}">

                                    @if(isset($timeline[$slot][$staff->id]))
                                    @foreach($timeline[$slot][$staff->id] as $res)

                                    @php
                                    $dbStartTime = \Carbon\Carbon::parse($selectedDate . ' ' . $res->reservation_time);
                                    $slotTime = \Carbon\Carbon::parse($selectedDate . ' ' . $slot);

                                    // 【判定】時間が一致し、かつメニュー情報を持っている（予約の起点）場合のみボタンを出す
                                    $shouldShowButton = $dbStartTime->equalTo($slotTime) && $res->menus->isNotEmpty();
                                    @endphp

                                    <div class="p-1 mb-1 border-l-2 relative transition-all duration-200
    {{ $staff->id == 0 || $staff->name == '指名なし' ? 'bg-red-50/50 border-red-400' : 'bg-white border-charcoal shadow-sm hover:shadow-md' }}">

                                        {{-- 削除ボタン --}}
                                        @if($shouldShowButton)
                                        <div class="absolute -top-1 -right-1 z-[50]">
                                            <button type="button"
                                                onclick="openDeleteModal('{{ $res->id }}')"
                                                class="flex bg-red-500 text-white rounded-full w-5 h-5 text-[10px] items-center justify-center hover:bg-red-700 transition shadow-sm cursor-pointer border border-white opacity-0 group-hover:opacity-100 transition-opacity">
                                                ×
                                            </button>
                                        </div>
                                        @endif

                                        {{-- 顧客名 --}}
                                        <p class="text-[11px] font-bold leading-none {{ $staff->id == 0 || $staff->name == '指名なし' ? 'text-red-900' : 'text-gray-800' }}">
                                            {{ $res->user->name }} <span class="text-[9px] font-normal text-gray-500">様</span>
                                        </p>

                                        {{-- メニュー名 --}}
                                        @if($shouldShowButton && $res->menus->isNotEmpty())
                                        <p class="text-[9px] text-gray-400 mt-1 leading-tight truncate">
                                            {{ $res->menus->pluck('name')->implode(', ') }}
                                        </p>
                                        @endif

                                        {{-- スタッフ割り当てボタン --}}
                                        @if($shouldShowButton)
                                        {{-- 指名なしの場合 --}}
                                        @if($staff->id == 0 || $staff->name == '指名なし')
                                        <button type="button"
                                            onclick="openModal({{ $res->id }})"
                                            class="mt-1 text-[8px] tracking-tighter text-red-500 font-bold border border-red-200 px-1 py-0.5 hover:bg-red-500 hover:text-white transition rounded-sm">
                                            ASSIGN STAFF
                                        </button>
                                        @else
                                        {{-- すでに担当者が決まっている場合（変更用） --}}
                                        <button type="button"
                                            onclick="openModal({{ $res->id }})"
                                            class="mt-1 text-[8px] tracking-tighter text-gray-400 font-normal border border-gray-200 px-1 py-0.5 hover:bg-black hover:text-white transition rounded-sm">
                                            CHANGE
                                        </button>
                                        @endif
                                        @endif
                                    </div>
                                    @endforeach
                                    @endif
                                </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- モーダル：アサイン用 --}}
    <div id="assignModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-[100] flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-xl max-w-md w-full">
            <h3 class="text-lg font-light tracking-widest uppercase mb-4">Assign Staff</h3>
            <form id="assignForm" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" name="reservation_id" id="modal_reservation_id">
                <div class="mb-6">
                    <label class="block text-[10px] text-gray-400 uppercase mb-2 text-left">Select Stylist</label>
                    <select name="staff_id" class="w-full border-gray-200 focus:border-black focus:ring-0 text-sm">
                        @foreach($staffs as $s)
                        @if($s->name !== '指名なし')
                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                        @endif
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end gap-4">
                    <button type="button" onclick="closeModal()" class="text-[10px] uppercase tracking-widest text-gray-400 hover:text-black transition">Cancel</button>
                    <button type="submit" class="bg-black text-white px-6 py-2 text-[10px] tracking-widest uppercase hover:bg-gray-800 transition">Update</button>
                </div>
            </form>
        </div>
    </div>

    {{-- モーダル：削除確認用 --}}
    <div id="deleteConfirmModal" class="hidden fixed inset-0 bg-black/40 backdrop-blur-[2px] z-[110] flex items-center justify-center p-4">
        <div class="bg-white p-10 max-w-sm w-full shadow-2xl text-center border border-gray-100">
            <h4 class="text-sm font-light tracking-[0.2em] text-gray-800 mb-6 uppercase">Confirm Cancel</h4>
            <p class="text-[11px] text-gray-500 mb-10 leading-relaxed tracking-wider">
                予約をキャンセルしてもよろしいですか？<br>この操作は取り消せません。
            </p>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex flex-col gap-3">
                    <button type="submit" class="bg-black text-white py-3 text-[10px] tracking-[0.3em] uppercase hover:bg-gray-800 transition-all duration-300">
                        Cancel Reservation
                    </button>
                    <button type="button" onclick="closeDeleteModal()" class="text-[10px] text-gray-400 uppercase tracking-[0.2em] hover:text-black py-2 transition-all duration-300">
                        Keep Booking
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(reservationId) {
            document.getElementById('modal_reservation_id').value = reservationId;
            document.getElementById('assignForm').action = "/admin/reservations/" + reservationId + "/assign";
            document.getElementById('assignModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('assignModal').classList.add('hidden');
        }

        function openDeleteModal(reservationId) {
            const form = document.getElementById('deleteForm');
            form.action = "/admin/reservations/" + reservationId;
            document.getElementById('deleteConfirmModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteConfirmModal').classList.add('hidden');
        }
    </script>
</x-app-layout>