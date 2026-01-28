<x-app-layout>
    <div class="py-24 bg-white text-center min-h-[80vh] flex flex-col justify-center opacity-0 animate-[fadeIn_1.5s_ease-out_forwards]">

        {{-- メインタイトル:あえて少し小さく、間隔を広く --}}
        <h2 class="text-2xl font-light tracking-[0.6em] text-gray-900 mb-4 uppercase italic staff-name">
            Thank you.
        </h2>
        <p class="text-[10px] tracking-[0.3em] text-gray-400 uppercase mb-16">Reservation Complete</p>

        {{-- 予約概要 --}}
        <div class="mb-20">
            <p class="text-gray-500 text-[11px] tracking-[0.2em] leading-[3] font-light">
                ご予約ありがとうございます。<br>
                <span class="text-gray-900 font-medium border-b border-gray-200 pb-1">
                    {{ Auth::user()->name }} 様
                </span>
                のご来店を、<br>
                スタッフ一同楽しみにしております。
            </p>
        </div>

        {{-- アクションボタン --}}
        <div>
            <a href="{{ route('dashboard') }}" class="group inline-block border border-gray-900 text-gray-900 px-16 py-5 text-[10px] tracking-[0.4em] uppercase hover:bg-gray-900 hover:text-white transition-all duration-700">
                <span class="inline-block transform group-hover:-translate-x-1 transition-transform duration-500 mr-2">←</span>
                Back to My Page
            </a>
        </div>
    </div>

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</x-app-layout>