<x-guest-layout>
    <div class="min-h-screen bg-[#faf9f6] flex items-center justify-center p-4 sm:p-6">
        <div class="max-w-md w-full bg-white p-8 sm:p-12 shadow-sm border border-gray-100">

            <div class="mb-8 text-center sm:text-left">
                <a href="{{ url('/') }}"
                    class="group inline-flex items-center text-[9px] sm:text-[10px] text-gray-400 hover:text-gray-800 transition-all tracking-[0.2em] uppercase">
                    <span class="mr-2 transition-transform duration-300 group-hover:-translate-x-1">←</span>
                    Back to Home <span class="ml-2 tracking-normal opacity-70 font-normal">/ トップへ</span>
                </a>
            </div>
            <div class="text-center mb-10 sm:mb-12">
                <h2 class="text-xl sm:text-2xl font-light tracking-[0.3em] text-gray-800 uppercase italic">Login</h2>
                <div class="w-8 h-[1px] bg-gray-200 mx-auto mt-4"></div>
            </div>

            <form id="login-form" method="POST" action="{{ route('login') }}" class="space-y-6 sm:space-y-8">
                @csrf
                <div>
                    <label
                        class="block whitespace-nowrap text-[10px] sm:text-sm tracking-[0.1em] sm:tracking-[0.2em] uppercase text-gray-600 mb-2 font-medium">
                        Email Address <span class="text-[9px] sm:text-xs opacity-80 ml-1 font-normal">/ メールアドレス</span>
                    </label>
                    <input type="email" id="email" name="email"
                        class="w-full border-0 border-b border-gray-300 focus:ring-0 focus:border-gray-800 transition-all p-0 pb-2 text-sm sm:text-base text-gray-900 placeholder-gray-300"
                        required autofocus>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="mt-6 sm:mt-8">
                    <label
                        class="block whitespace-nowrap text-[10px] sm:text-sm tracking-[0.1em] sm:tracking-[0.2em] uppercase text-gray-600 mb-2 font-medium">
                        Password <span class="text-[9px] sm:text-xs opacity-80 ml-1 font-normal">/ パスワード</span>
                    </label>
                    <input type="password" id="password" name="password"
                        class="w-full border-0 border-b border-gray-300 focus:ring-0 focus:border-gray-800 transition-all p-0 pb-2 text-sm sm:text-base text-gray-900 placeholder-gray-300"
                        required>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
                <div class="pt-6 sm:pt-8">
                    <button type="submit"
                        class="w-full bg-gray-800 text-white py-4 text-[10px] sm:text-xs tracking-[0.4em] uppercase hover:bg-gray-700 transition-all duration-500">
                        Sign In
                    </button>
                </div>
            </form>

            <div class="mt-10 pt-8 border-t border-gray-100">
                <p class="text-center text-[9px] sm:text-[10px] text-gray-400 mb-4 tracking-[0.2em] uppercase">
                    Demo Login <span class="tracking-normal opacity-80">/ デモ体験</span>
                </p>
                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="button" onclick="demoLogin('test@test', '123456789')"
                        class="w-full border border-gray-200 text-gray-500 py-3 text-[9px] sm:text-[10px] tracking-[0.1em] hover:bg-gray-50 hover:text-gray-800 transition-all duration-300">
                        一般のお客様（デモ）
                    </button>
                    <button type="button" onclick="demoLogin('admin@admin', '123456789')"
                        class="w-full border border-gray-200 text-gray-500 py-3 text-[9px] sm:text-[10px] tracking-[0.1em] hover:bg-gray-50 hover:text-gray-800 transition-all duration-300">
                        サロン管理者（デモ）
                    </button>
                </div>
            </div>
            <div class="mt-10 sm:mt-12 flex justify-center">
                <a href="{{ route('register') }}"
                    class="group flex items-center whitespace-nowrap text-[10px] sm:text-xs text-gray-400 hover:text-gray-800 transition-all border-b border-gray-100 pb-1 px-1">
                    <span class="tracking-[0.2em] uppercase">Create New Account</span>
                    <span class="ml-2 tracking-normal opacity-70 font-normal">/ 新規登録はこちら</span>
                    <span class="ml-2 transition-transform duration-300 group-hover:translate-x-1">→</span>
                </a>
            </div>
        </div>
    </div>

    <script>
        function demoLogin(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
            document.getElementById('login-form').submit();
        }
    </script>
</x-guest-layout>
