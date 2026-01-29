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
            @if (Route::has('login'))
            @auth
            <a href="{{ url('/dashboard') }}" class="hover:text-gray-400 transition">My Page</a>
            @else
            <a href="{{ route('login') }}" class="hover:text-gray-400 transition">Login</a>
            @if (Route::has('register'))
            <a href="{{ route('register') }}" class="hover:text-gray-400 transition">Register</a>
            @endif
            @endauth
            @endif
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

            <a href="{{ route('booking.gate') }}" class="inline-block border border-white text-white px-12 py-4 text-xs tracking-[0.3em] hover:bg-white hover:text-gray-900 transition duration-500 uppercase backdrop-blur-sm">
                Book Now / オンライン予約
            </a>
        </div>
    </div>

    <section class="py-32 bg-white">
        <div class="px-8 max-w-4xl mx-auto text-center">

            <div class="w-[1px] h-16 bg-gray-200 mx-auto mb-12"></div>

            <h2 class="text-3xl font-light tracking-[0.4em] text-gray-800 uppercase mb-2">
                CONCEPT
                <span class="text-[10px] tracking-[0.3em] text-gray-400 italic uppercase">
                    Our Philosophy</span>
            </h2>

            <div class="space-y-10 max-w-2xl mx-auto">
                <p class="leading-[2.8] text-gray-600 font-light text-sm md:text-base tracking-widest">
                    喧騒を忘れさせるプライベートな空間で、<br>
                    熟練のスタイリストが一人ひとりの骨格と毛流れを読み解き、<br>
                    あなた本来の美しさを引き出します。
                </p>

                <div class="pt-8">
                    <span class="text-gray-300 text-xs">◆</span>
                </div>
            </div>
        </div>
    </section>
</body>

</html>