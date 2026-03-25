<x-guest-layout>
    <div class="min-h-screen bg-[#faf9f6] flex items-center justify-center p-4 sm:p-6">
        <div class="max-w-md w-full bg-white p-8 sm:p-12 shadow-sm border border-gray-100">
            <div class="text-center mb-10 sm:mb-12">
                <h2 class="text-xl sm:text-2xl font-light tracking-[0.3em] text-gray-800 uppercase italic">Register</h2>
                <div class="w-8 h-[1px] bg-gray-200 mx-auto mt-4"></div>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-6 sm:space-y-8">
                @csrf
                <div>
                    <label class="block text-xs sm:text-sm tracking-[0.2em] uppercase text-gray-700 mb-2 font-medium">
                        Name <span class="text-[10px] sm:text-xs opacity-80 ml-1 font-normal whitespace-nowrap">/
                            お名前</span>
                    </label>
                    <input type="text" name="name"
                        class="w-full border-0 border-b border-gray-300 focus:ring-0 focus:border-gray-800 transition-all p-0 pb-2 text-sm sm:text-base text-gray-900 placeholder-gray-300"
                        required>
                </div>

                <div class="mt-6 sm:mt-8">
                    <label class="block text-xs sm:text-sm tracking-[0.2em] uppercase text-gray-700 mb-2 font-medium">
                        Email <span class="text-[10px] sm:text-xs opacity-80 ml-1 font-normal whitespace-nowrap">/
                            メールアドレス</span>
                    </label>
                    <input type="email" name="email"
                        class="w-full border-0 border-b border-gray-300 focus:ring-0 focus:border-gray-800 transition-all p-0 pb-2 text-sm sm:text-base text-gray-900 placeholder-gray-300"
                        required>
                </div>

                <div class="mt-6 sm:mt-8">
                    <label class="block text-xs sm:text-sm tracking-[0.2em] uppercase text-gray-700 mb-2 font-medium">
                        Password <span class="text-[10px] sm:text-xs opacity-80 ml-1 font-normal whitespace-nowrap">/
                            パスワード</span>
                    </label>
                    <input type="password" name="password"
                        class="w-full border-0 border-b border-gray-300 focus:ring-0 focus:border-gray-800 transition-all p-0 pb-2 text-sm sm:text-base text-gray-900 placeholder-gray-300"
                        required>
                </div>

                <div class="mt-6 sm:mt-8">
                    <label class="block text-xs sm:text-sm tracking-[0.2em] uppercase text-gray-700 mb-2 font-medium">
                        Confirm Password <span
                            class="text-[10px] sm:text-xs opacity-80 ml-1 font-normal whitespace-nowrap">/
                            確認用パスワード</span>
                    </label>
                    <input type="password" name="password_confirmation"
                        class="w-full border-0 border-b border-gray-300 focus:ring-0 focus:border-gray-800 transition-all p-0 pb-2 text-sm sm:text-base text-gray-900 placeholder-gray-300"
                        required>
                </div>

                <div class="pt-6 sm:pt-8">
                    <button type="submit"
                        class="w-full bg-gray-800 text-white py-4 text-[10px] sm:text-xs tracking-[0.4em] uppercase hover:bg-gray-700 transition-all duration-500">
                        Create Account
                    </button>
                </div>

                <div class="mt-10 sm:mt-12 flex justify-center">
                    <a href="{{ route('booking.gate') }}"
                        class="group flex items-center whitespace-nowrap text-[10px] sm:text-xs text-gray-400 hover:text-gray-800 transition-all border-b border-gray-100 pb-1 px-1">
                        <span class="mr-2 transition-transform duration-300 group-hover:-translate-x-1">←</span>

                        <span class="tracking-[0.2em] uppercase">Back to Selection</span>

                        <span class="ml-2 tracking-normal opacity-70 font-normal">/ 前の画面に戻る</span>
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
