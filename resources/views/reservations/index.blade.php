<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('予約お申し込み') }}
        </h2>
    </x-slot>


    <div class="py-12 bg-[#fafafa]">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            {{-- マイページに戻るボタン --}}
            <div class="mb-8 px-4">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center text-[10px] tracking-[0.3em] text-gray-400 hover:text-black transition-all duration-300 group">
                    <svg class="w-4 h-4 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    BACK TO MY PAGE
                </a>
            </div>

            <form action="{{ route('reservations.datetime') }}" method="GET">

                {{-- 01. Select Menu --}}
                <h3 class="text-sm font-bold mb-6 tracking-widest text-gray-400 uppercase">01. Select Menu</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-16">
                    @foreach($menus as $menu)
                    <label class="group relative bg-white border border-gray-200 p-6 cursor-pointer hover:border-black transition-all duration-300 has-[:checked]:border-black has-[:checked]:ring-1 has-[:checked]:ring-black shadow-sm">
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

                {{-- 02. Select Stylist --}}
                <h3 class="text-sm font-bold mb-8 tracking-widest text-gray-400 uppercase">02. Select Stylist</h3>

                {{-- ここが重要：gridを一つだけに整理しました --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-8 mb-20">

                    {{-- ANY STAFF (指名なし) --}}
                    <label class="group relative cursor-pointer text-center">
                        <input type="radio" name="staff_id" value="0" class="hidden peer" required>
                        <div class="relative w-24 h-24 md:w-32 md:h-32 mx-auto mb-4 overflow-hidden rounded-full border border-gray-100 transition-all duration-300 bg-black text-white flex items-center justify-center 
                            group-hover:ring-2 group-hover:ring-black group-hover:ring-offset-2
                            peer-checked:bg-gray-700 peer-checked:ring-2 peer-checked:ring-black peer-checked:ring-offset-2 shadow-md">
                            <span class="text-[9px] tracking-[0.2em] font-light">ANY STAFF</span>
                        </div>
                        <p class="text-sm md:text-base font-bold text-gray-900">指名なし</p>
                        <p class="text-[10px] text-gray-400 mt-1 px-1 italic leading-tight">No designated stylist</p>
                    </label>

                    @foreach($staffs as $staff)
                    <label class="group relative cursor-pointer text-center block">
                        {{-- ラジオボタン --}}
                        <input type="radio" name="staff_id" value="{{ $staff->id }}" class="hidden peer" required>

                        {{-- 画像コンテナ --}}
                        <div class="relative w-24 h-24 md:w-32 md:h-32 mx-auto mb-4 rounded-full border border-gray-100 transition-all duration-700
                                    group-hover:ring-1 group-hover:ring-black group-hover:ring-offset-4
                                    peer-checked:ring-1 peer-checked:ring-black peer-checked:ring-offset-4 peer-checked:shadow-sm overflow-hidden">

                            {{-- 画像 --}}
                            <img src="{{ asset('images/staff' . $staff->id . '.png') }}"
                                alt="{{ $staff->name }}"
                                id="img-staff-{{ $staff->id }}"
                                class="js-staff-image w-full h-full object-cover transition-all duration-1000 grayscale group-hover:grayscale-0">
                        </div>

                        <p class="text-sm md:text-base font-medium text-gray-400 tracking-[0.1em] transition-all duration-500
                                    group-hover:text-black
                                    peer-checked:text-black peer-checked:tracking-[0.25em]">
                            {{ $staff->name }}
                        </p>

                        {{-- 選択中にだけ細いラインが出るようにする --}}
                        <div class="w-0 h-[1px] bg-black mx-auto mt-2 transition-all duration-700 peer-checked:w-6"></div>

                        <div class="mt-3 px-1 min-h-[80px]">
                            <p class="text-[10px] leading-relaxed text-gray-400 font-light italic transition-all duration-500
                                      group-hover:text-gray-600 peer-checked:text-gray-800">
                                @if($staff->id == 1)
                                <span class="block text-gray-300 font-bold uppercase tracking-widest text-[7px] mb-1 not-italic group-hover:text-gray-400 peer-checked:text-gray-500">Owner / Director</span>
                                一人ひとりの骨格に寄り添う、唯一無二のシルエットを。
                                @elseif($staff->id == 2)
                                <span class="block text-gray-300 font-bold uppercase tracking-widest text-[7px] mb-1 not-italic group-hover:text-gray-400 peer-checked:text-gray-500">Color Specialist</span>
                                透明感と柔らかな質感。光に溶け込むカラーをご提供します。
                                @elseif($staff->id == 3)
                                <span class="block text-gray-300 font-bold uppercase tracking-widest text-[7px] mb-1 not-italic group-hover:text-gray-400 peer-checked:text-gray-500">Men's Expert</span>
                                洗練されたエッジ。日常を格上げするメンズスタイルを再現します。
                                @else
                                {{ $staff->description }}
                                @endif
                            </p>
                        </div>
                    </label>
                    @endforeach
                </div>

                {{-- Confirm Button Section --}}
                <div class="text-center mt-12 pb-20 border-t border-gray-100 pt-12">
                    <button type="submit" class="bg-black text-white text-[10px] tracking-[0.3em] px-16 py-5 uppercase hover:bg-gray-800 transition-all duration-300 shadow-xl">
                        SELECT DATE & TIME / 日時選択
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- color固定script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const radioButtons = document.querySelectorAll('input[name="staff_id"]');
            const allImages = document.querySelectorAll('.js-staff-image');

            function updateColors() {
                // 一旦すべてをモノクロに戻す
                allImages.forEach(img => {
                    img.classList.remove('grayscale-0');
                    img.classList.add('grayscale');
                });

                // 選択されたラジオボタンを取得
                const selected = document.querySelector('input[name="staff_id"]:checked');

                // 選択されたのがスタッフ（ID 0以外）なら、その画像だけ grayscaleを消す
                if (selected && selected.value !== "0") {
                    const targetImg = document.getElementById('img-staff-' + selected.value);
                    if (targetImg) {
                        targetImg.classList.remove('grayscale');
                        targetImg.classList.add('grayscale-0'); // これで強制的にカラー化

                    }
                }
            }

            radioButtons.forEach(radio => {
                radio.addEventListener('change', updateColors);
            });

            // 初期化（戻るボタンなどで状態が残っていた場合用）
            updateColors();
        });
    </script>
</x-app-layout>