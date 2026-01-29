<x-app-layout>
    <x-slot name="header">
        <h2 class="font-light text-xl text-gray-800 tracking-[0.2em] uppercase">
            Admin Dashboard <span class="text-xs opacity-50">/ 管理者画面</span>
        </h2>
    </x-slot>

    <div class="py-12 bg-[#faf9f6] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm p-8">
                <h3 class="text-sm font-medium mb-6 tracking-widest uppercase italic">Reservation List / 予約一覧</h3>

                {{-- ここに予約のテーブルを作成 --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-100 text-[10px] uppercase tracking-widest text-gray-400">
                                <th class="pb-4 font-medium">Date / Time</th>
                                <th class="pb-4 font-medium">Customer</th>
                                <th class="pb-4 font-medium">Menu</th>
                                <th class="pb-4 font-medium">Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @forelse($reservations as $reservation)
                            <tr class="border-b border-gray-50 last:border-0 hover:bg-gray-50/50 transition-colors">
                                <td class="py-4 font-light">
                                    {{ $reservation->reservation_date }} <br>
                                    <span class="text-xs text-gray-400">{{ $reservation->reservation_time }}</span>
                                </td>
                                <td class="py-4">
                                    {{ $reservation->user->name }}
                                    <div class="text-[10px] text-gray-400">{{ $reservation->user->email }}</div>
                                </td>
                                <td class="py-4 text-xs">
                                    @foreach($reservation->menus as $menu)
                                    <span class="inline-block bg-gray-100 px-2 py-0.5 rounded-sm mr-1 mb-1">{{ $menu->name }}</span>
                                    @endforeach
                                </td>
                                <td class="py-4">
                                    <span class="text-[10px] px-2 py-1 bg-black text-white rounded-full uppercase tracking-tighter">Confirmed</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-10 text-center text-gray-400 text-xs tracking-widest">
                                    現在、予約データはありません。
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>