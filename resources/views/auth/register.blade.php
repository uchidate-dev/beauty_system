<x-guest-layout>
    <div class="min-h-screen bg-[#faf9f6] flex items-center justify-center p-6">
        <div class="max-w-md w-full bg-white p-12 shadow-sm border border-gray-100">
            <div class="text-center mb-12">
                <h2 class="text-2xl font-light tracking-[0.3em] text-gray-800 uppercase italic">Register</h2>
                <div class="w-8 h-[1px] bg-gray-200 mx-auto mt-4"></div>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-8 text-sm">
                @csrf
                <div>
                    <label class="block text-[10px] tracking-[0.2em] uppercase text-gray-400 mb-2">Name</label>
                    <input type="text" name="name" class="w-full border-0 border-b border-gray-200 focus:ring-0 focus:border-gray-800 transition-all p-0 pb-2" required>
                </div>

                <div>
                    <label class="block text-[10px] tracking-[0.2em] uppercase text-gray-400 mb-2">Email</label>
                    <input type="email" name="email" class="w-full border-0 border-b border-gray-200 focus:ring-0 focus:border-gray-800 transition-all p-0 pb-2" required>
                </div>

                <div>
                    <label class="block text-[10px] tracking-[0.2em] uppercase text-gray-400 mb-2">Password</label>
                    <input type="password" name="password" class="w-full border-0 border-b border-gray-200 focus:ring-0 focus:border-gray-800 transition-all p-0 pb-2" required>
                </div>

                <div>
                    <label class="block text-[10px] tracking-[0.2em] uppercase text-gray-400 mb-2">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="w-full border-0 border-b border-gray-200 focus:ring-0 focus:border-gray-800 transition-all p-0 pb-2" required>
                </div>

                <div class="pt-6">
                    <button type="submit" class="w-full bg-gray-800 text-white py-4 text-[10px] tracking-[0.4em] uppercase hover:bg-gray-700 transition-all duration-500">
                        Create Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>