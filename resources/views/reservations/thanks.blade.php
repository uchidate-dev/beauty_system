<x-app-layout>
    <div class="py-24 bg-white text-center">
        <h2 class="text-3xl font-light tracking-[0.3em] text-gray-800 mb-6 uppercase">
            Reservation Complete
        </h2>

        <p class="text-gray-500 text-sm mb-12 tracking-widest leading-loose">
            ご予約ありがとうございます。<br>
            当日お会いできるのをスタッフ一同楽しみにしております。
        </p>

        <a href="{{ route('dashboard') }}" class="inline-block border border-black text-black px-12 py-4 text-[10px] tracking-[0.3em] uppercase hover:bg-black hover:text-white transition-all duration-500">
            Back to Home
        </a>
    </div>
</x-app-layout>