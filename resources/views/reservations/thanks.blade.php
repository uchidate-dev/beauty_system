<x-app-layout>
    <div class="py-24 bg-white text-center min-h-[60vh] flex flex-col justify-center">
        <h2 class="text-3xl font-light tracking-[0.5em] text-gray-900 mb-8 uppercase italic staff-name">
            Reservation Complete
        </h2>

        <p class="text-gray-500 text-[11px] mb-16 tracking-[0.2em] leading-[3] font-light">
            ご予約ありがとうございます。<br>
            当日お会いできるのをスタッフ一同楽しみにしております。
        </p>

        <div>
            <a href="{{ route('dashboard') }}" class="inline-block border border-gray-900 text-gray-900 px-16 py-5 text-[10px] tracking-[0.4em] uppercase hover:bg-gray-900 hover:text-white transition-all duration-700">
                Back to My Page
            </a>
        </div>
    </div>
</x-app-layout>