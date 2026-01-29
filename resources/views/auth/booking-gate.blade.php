<x-guest-layout>
    <div class="min-h-screen bg-[#faf9f6] flex items-center justify-center px-6">
        <div class="max-w-md w-full text-center">
            <h2 class="text-3xl font-light tracking-[0.3em] mb-4 text-gray-800 uppercase">Welcome</h2>
            <p class="text-[10px] tracking-[0.2em] text-gray-400 uppercase mb-16 italic">To provide you with the best experience.</p>

            <div class="space-y-6">
                <a href="{{ route('login') }}" class="block w-full border border-gray-800 bg-gray-800 text-white py-5 text-[10px] tracking-[0.4em] uppercase hover:bg-white hover:text-gray-800 transition-all duration-500">
                    Login / 会員の方
                </a>

                <div class="flex items-center justify-center gap-4 py-4">
                    <div class="h-[1px] w-8 bg-gray-200"></div>
                    <span class="text-[9px] text-gray-300 tracking-widest uppercase">or</span>
                    <div class="h-[1px] w-8 bg-gray-200"></div>
                </div>

                <a href="{{ route('register') }}" class="block w-full border border-gray-800 text-gray-800 py-5 text-[10px] tracking-[0.4em] uppercase hover:bg-gray-800 hover:text-white transition-all duration-500">
                    Register / 初めての方
                </a>
            </div>

            <a href="/" class="inline-block mt-16 text-[9px] text-gray-400 tracking-[0.2em] uppercase border-b border-gray-200 pb-1">
                Back to home
            </a>
        </div>
    </div>
</x-guest-layout>