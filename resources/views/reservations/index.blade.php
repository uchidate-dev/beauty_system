<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('予約お申し込み') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <h3 class="text-lg font-bold mb-4">1. メニューを選択</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                    @foreach($menus as $menu)
                    <div class="border p-4 rounded hover:bg-pink-50 cursor-pointer">
                        <p class="font-bold">{{ $menu->name }}</p>
                        <p class="text-sm text-gray-600">{{ number_format($menu->price) }}円（{{ $menu->duration }}分）</p>
                    </div>
                    @endforeach
                </div>

                <h3 class="text-lg font-bold mb-4">2. スタッフを選択</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($staffs as $staff)
                    <div class="border p-4 rounded hover:bg-blue-50 cursor-pointer text-center">
                        <div class="w-20 h-20 bg-gray-200 rounded-full mx-auto mb-2"></div>
                        <p class="font-bold">{{ $staff->name }}</p>
                        <p class="text-xs text-gray-500">{{ $staff->description }}</p>
                    </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
</x-app-layout>