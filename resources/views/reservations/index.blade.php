<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('予約お申し込み') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('reservations.datetime') }}" method="GET">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                    <h3 class="text-lg font-bold mb-4 italic text-pink-600">1. メニューを選択（複数選択可）</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                        @foreach($menus as $menu)
                        <label class="border-2 p-4 rounded-xl hover:bg-pink-50 cursor-pointer flex items-center transition-all border-gray-100 has-[:checked]:border-pink-400 has-[:checked]:bg-pink-50">
                            <input type="checkbox" name="menu_ids[]" value="{{ $menu->id }}" class="w-5 h-5 mr-3 text-pink-500 rounded border-gray-300 focus:ring-pink-500">
                            <div class="flex-1">
                                <p class="font-bold text-gray-800">{{ $menu->name }}</p>
                                <p class="text-sm text-gray-600">{{ number_format($menu->price) }}円（{{ $menu->duration }}分）</p>
                            </div>
                        </label>
                        @endforeach
                    </div>

                    <h3 class="text-lg font-bold mb-4 italic text-blue-600">2. スタッフを選択</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                        @foreach($staffs as $staff)
                        <label class="border-2 p-4 rounded-xl hover:bg-blue-50 cursor-pointer text-center block transition-all border-gray-100 has-[:checked]:border-blue-400 has-[:checked]:bg-blue-50">
                            <input type="radio" name="staff_id" value="{{ $staff->id }}" id="staff_{{ $staff->id }}" class="mb-3 w-5 h-5 text-blue-500 focus:ring-blue-500" required>
                            <div class="w-16 h-16 bg-gray-200 rounded-full mx-auto mb-2 flex items-center justify-center text-xs text-gray-400 font-bold shadow-inner">PHOTO</div>
                            <p class="font-bold text-gray-800">{{ $staff->name }}</p>
                            <p class="text-xs text-gray-500 line-clamp-2 px-2">{{ $staff->description }}</p>
                        </label>
                        @endforeach
                    </div>

                    <div class="text-center mt-10">
                        <button type="submit" class="inline-flex items-center px-10 py-4 bg-gradient-to-r from-gray-800 to-gray-700 border border-transparent rounded-full font-semibold text-white uppercase tracking-widest hover:from-gray-700 hover:to-gray-600 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg">
                            日時選択へ進む ➔
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</x-app-layout>