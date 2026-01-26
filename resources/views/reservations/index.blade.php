<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('予約お申し込み') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-[#fafafa]">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('reservations.datetime') }}" method="GET">

                <h3 class="text-sm font-bold mb-6 tracking-widest text-gray-400 uppercase">01. Select Menu</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
                    @foreach($menus as $menu)
                    <label class="group relative bg-white border border-gray-200 p-6 cursor-pointer hover:border-black transition-all duration-300 has-[:checked]:border-black has-[:checked]:ring-1 has-[:checked]:ring-black">
                        <input type="checkbox" name="menu_ids[]" value="{{ $menu->id }}" class="hidden">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-lg font-medium text-gray-900">{{ $menu->name }}</p>
                                <p class="text-sm text-gray-400 mt-1">{{ $menu->duration }} min</p>
                            </div>
                            <p class="text-sm font-semibold text-gray-900">¥{{ number_format($menu->price) }}</p>
                        </div>
                    </label>
                    @endforeach
                </div>

                <h3 class="text-sm font-bold mb-6 tracking-widest text-gray-400 uppercase">02. Select Stylist</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">

                    <label class="group relative cursor-pointer text-center">
                        <input type="radio" name="staff_id" value="0" class="hidden" required>
                        <div class="relative w-32 h-32 mx-auto mb-4 overflow-hidden rounded-full border border-gray-100 group-hover:border-black transition-all has-[:checked]:border-black has-[:checked]:ring-2 has-[:checked]:ring-black flex items-center justify-center bg-black text-white">
                            <span class="text-[10px] tracking-[0.3em] font-light">ANY STAFF</span>
                        </div>
                        <p class="text-lg font-medium text-gray-900">指名なし</p>
                        <p class="text-xs text-gray-400 mt-1 px-4 italic">No designated stylist</p>
                    </label>

                    @foreach($staffs as $staff)
                    <label class="group relative cursor-pointer text-center">
                        <input type="radio" name="staff_id" value="{{ $staff->id }}" class="hidden" required>
                        <div class="relative w-32 h-32 mx-auto mb-4 overflow-hidden rounded-full border border-gray-100 group-hover:border-black transition-all has-[:checked]:border-black has-[:checked]:ring-2 has-[:checked]:ring-black">
                            {{-- 本来は画像を入れる場所 --}}
                            <div class="w-full h-full bg-gray-50 flex items-center justify-center text-gray-300">PHOTO</div>
                        </div>
                        <p class="text-lg font-medium text-gray-900">{{ $staff->name }}</p>
                        <p class="text-xs text-gray-400 mt-1 leading-relaxed px-4">{{ $staff->description }}</p>
                    </label>
                    @endforeach
                </div>

                <div class="text-center mt-16">
                    <button type="submit" class="bg-black text-white text-xs tracking-[0.2em] px-12 py-5 uppercase hover:bg-gray-800 transition-all duration-300 shadow-sm">
                        Confirm Selection
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>