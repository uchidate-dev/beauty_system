<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            予約日時の選択
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <div class="mb-8 p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <h3 class="text-sm font-bold text-gray-500 mb-2 uppercase tracking-wider">選択中の内容</h3>
                    <p class="text-lg text-gray-800">
                        担当：<span class="font-bold text-blue-600">{{ $staff->name }}</span>
                    </p>
                    <p class="text-md text-gray-700 mt-1">
                        メニュー：
                        @foreach($menus as $menu)
                        <span class="inline-block bg-pink-100 text-pink-700 px-2 py-1 rounded text-xs font-bold mr-1">{{ $menu->name }}</span>
                        @endforeach
                    </p>
                </div>

                <form action="{{ route('reservations.store') }}" method="POST">
                    @csrf

                    <input type="hidden" name="staff_id" value="{{ $staff->id }}">
                    @foreach($menus as $menu)
                    <input type="hidden" name="menu_ids[]" value="{{ $menu->id }}">
                    @endforeach

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">1. 日付を選んでください</label>
                            <input type="date" name="reservation_date" class="w-full border-gray-300 rounded-md shadow-sm focus:border-pink-500 focus:ring-pink-500" required min="{{ date('Y-m-d') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">2. 時間を選んでください</label>
                            <select name="reservation_time" class="w-full border-gray-300 rounded-md shadow-sm focus:border-pink-500 focus:ring-pink-500" required>
                                <option value="">時間を選択</option>
                                <option value="10:00">10:00</option>
                                <option value="11:00">11:00</option>
                                <option value="12:00">12:00</option>
                                <option value="13:00">13:00</option>
                                <option value="14:00">14:00</option>
                                <option value="15:00">15:00</option>
                                <option value="16:00">16:00</option>
                                <option value="17:00">17:00</option>
                                <option value="18:00">18:00</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-10 text-center">
                        <button type="submit" class="w-full md:w-auto px-12 py-4 bg-pink-600 text-white font-bold rounded-full hover:bg-pink-700 shadow-lg transition">
                            予約を確定する
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>