<section>
    <header>
        <h2 class="text-lg font-light tracking-widest text-gray-900 uppercase">
            Profile Information <span class="text-xs opacity-50">/ プロフィール情報</span>
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            お名前とメールアドレスの確認・変更ができます。
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="space-y-6">
            <div>
                <label class="block text-[10px] tracking-[0.2em] uppercase text-gray-600 mb-2 font-medium">
                    Name <span class="text-[8px] opacity-80">/ お名前</span>
                </label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                    class="w-full border-0 border-b border-gray-300 focus:ring-0 focus:border-gray-800 transition-all p-0 pb-2 text-gray-900 text-base">
            </div>

            <div class="mt-8">
                <label class="block text-[10px] tracking-[0.2em] uppercase text-gray-600 mb-2 font-medium">
                    Email <span class="text-[8px] opacity-80">/ メールアドレス</span>
                </label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                    class="w-full border-0 border-b border-gray-300 focus:ring-0 focus:border-gray-800 transition-all p-0 pb-2 text-gray-900 text-base">
            </div>
        </div>

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
        <div>
            <p class="text-sm mt-2 text-gray-800">
                メールアドレスが未認証です。
                <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900">
                    認証メールを再送する場合はこちらをクリックしてください。
                </button>
            </p>

            @if (session('status') === 'verification-link-sent')
            <p class="mt-2 font-medium text-sm text-green-600">
                新しい認証リンクをメールアドレスに送信しました。
            </p>
            @endif
        </div>
        @endif

        <div class="flex items-center gap-4 pt-6">
            <x-primary-button class="bg-black hover:bg-gray-800 text-white px-8 py-3 rounded-none tracking-widest text-xs transition uppercase">
                Update / 更新する
            </x-primary-button>

            @if (session('status') === 'profile-updated')
            <p
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 2000)"
                class="text-sm text-gray-500 italic">Saved / 保存しました。</p>
            @endif
        </div>
    </form>
</section>