<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Beauty Salon - Omotesando</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased bg-white text-gray-900 font-sans">

    <nav class="fixed w-full z-50 flex justify-between items-center px-8 py-6 bg-white/80 backdrop-blur-sm">
        <div class="text-xl font-light tracking-[0.3em]">BEAUTY SALON</div>
        <div class="space-x-8 text-xs tracking-widest uppercase">
            @auth
                <a href="{{ url('/dashboard') }}" class="hover:text-gray-400 transition">My Page</a>
            @else
                <a href="{{ route('booking.gate') }}" class="hover:text-gray-400 transition">Reserve</a>
            @endauth
        </div>
    </nav>

    <div class="relative h-screen w-full flex items-center justify-center overflow-hidden bg-gray-900">
        <div class="absolute inset-0 opacity-60 bg-[url('/images/hero.png')] bg-cover bg-center"></div>
        <div class="absolute inset-0 bg-black/20"></div>

        <div class="relative text-center z-10 px-4">
            <h1 class="text-5xl md:text-7xl font-light tracking-[0.2em] mb-6 text-white uppercase drop-shadow-lg">
                Timeless Beauty
            </h1>
            <p class="text-sm md:text-base tracking-[0.5em] text-gray-200 mb-12 uppercase drop-shadow-md">
                あなただけの特別な美しさを。
            </p>

            <a href="{{ route('booking.gate') }}"
                class="inline-block border border-white text-white px-12 py-4 text-xs tracking-[0.3em] hover:bg-white hover:text-gray-900 transition duration-500 uppercase backdrop-blur-sm">
                Book Now / オンライン予約
            </a>
        </div>
    </div>

    <section class="py-40 bg-[#faf9f6]">
        <div class="px-8 max-w-5xl mx-auto text-center">

            <div class="w-[1px] h-20 bg-gray-300 mx-auto mb-16"></div>

            <h2 class="text-4xl font-light tracking-[0.4em] text-gray-800 uppercase mb-4">
                Concept
            </h2>
            <span class="block text-[10px] tracking-[0.3em] text-gray-500 italic uppercase mb-20">
                Our Philosophy</span>

            <div class="space-y-12 max-w-3xl mx-auto">
                <p class="leading-[3] text-gray-700 font-light text-sm md:text-base tracking-[0.15em]">
                    喧騒を忘れさせるプライベートな空間で、<br>
                    熟練のスタイリストが一人ひとりの骨格と毛流れを読み解き、<br>
                    あなた本来の美しさを引き出します。
                </p>

                <div class="pt-10 flex justify-center text-gray-300">
                    <span class="text-xs tracking-[0.5em] opacity-50">◆ &nbsp; ◆ &nbsp; ◆</span>
                </div>
            </div>

        </div>
    </section>

    <footer class="bg-gray-900 text-white py-20 flex flex-col items-center border-t border-gray-800">
        <div class="text-center tracking-[0.2em] text-gray-400 uppercase">
            <div class="text-xl font-light tracking-[0.4em] text-white italic mb-8">
                BEAUTY SALON
            </div>

            <div class="space-y-3 text-[9px] sm:text-[10px]">
                <p>Omotesando, Tokyo</p>
                <p>Open 10:00 - 20:00 / Close Tue</p>
            </div>

            <div class="mt-16 text-[8px] opacity-60 space-y-2">
                <p>&copy; 2026 Beauty Salon. All rights reserved.</p>
                <p>Portfolio Project by Maiko</p>
            </div>
        </div>
    </footer>
</body>

</html>
