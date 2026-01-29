<section>
    <header>
        <h2 class="text-lg font-light tracking-widest text-gray-900 uppercase">
            Update Password <span class="text-xs opacity-50">/ パスワード更新</span>
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            安全のため、定期的かつ複雑なパスワードへの変更を推奨します。
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-8">
        @csrf
        @method('put')

        <div>
            <label class="block text-[10px] tracking-[0.2em] uppercase text-gray-600 mb-2 font-medium">
                Current Password <span class="text-[8px] opacity-80">/ 現在のパスワード</span>
            </label>
            <input id="update_password_current_password" name="current_password" type="password"
                class="w-full border-0 border-b border-gray-300 focus:ring-0 focus:border-gray-800 transition-all p-0 pb-2 text-gray-900" autocomplete="current-password">
            <x-input-error class="mt-2" :messages="$errors->updatePassword->get('current_password')" />
        </div>

        <div>
            <label class="block text-[10px] tracking-[0.2em] uppercase text-gray-600 mb-2 font-medium">
                New Password <span class="text-[8px] opacity-80">/ 新しいパスワード</span>
            </label>
            <input id="update_password_password" name="password" type="password"
                class="w-full border-0 border-b border-gray-300 focus:ring-0 focus:border-gray-800 transition-all p-0 pb-2 text-gray-900" autocomplete="new-password">
            <x-input-error class="mt-2" :messages="$errors->updatePassword->get('password')" />
        </div>

        <div>
            <label class="block text-[10px] tracking-[0.2em] uppercase text-gray-600 mb-2 font-medium">
                Confirm Password <span class="text-[8px] opacity-80">/ 新しいパスワード（確認）</span>
            </label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                class="w-full border-0 border-b border-gray-300 focus:ring-0 focus:border-gray-800 transition-all p-0 pb-2 text-gray-900" autocomplete="new-password">
            <x-input-error class="mt-2" :messages="$errors->updatePassword->get('password_confirmation')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button class="bg-black hover:bg-gray-800 text-white px-8 py-3 rounded-none tracking-widest text-xs transition uppercase">
                Save / 更新する
            </x-primary-button>

            @if (session('status') === 'password-updated')
            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-500 italic">Saved / 更新しました。</p>
            @endif
        </div>
    </form>
</section>