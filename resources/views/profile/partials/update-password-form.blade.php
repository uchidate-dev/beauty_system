<section>
    <header>
        <h2 class="text-lg font-light tracking-widest text-gray-900">
            パスワード更新
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            安全のため、定期的かつ複雑なパスワードへの変更を推奨します。
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" value="現在のパスワード" class="text-xs text-gray-400" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full border-gray-200 focus:border-black focus:ring-0 rounded-none" autocomplete="current-password" />
            <x-input-error class="mt-2" :messages="$errors->updatePassword->get('current_password')" />
        </div>

        <div>
            <x-input-label for="update_password_password" value="新しいパスワード" class="text-xs text-gray-400" />
            <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full border-gray-200 focus:border-black focus:ring-0 rounded-none" autocomplete="new-password" />
            <x-input-error class="mt-2" :messages="$errors->updatePassword->get('password')" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" value="新しいパスワード（確認）" class="text-xs text-gray-400" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full border-gray-200 focus:border-black focus:ring-0 rounded-none" autocomplete="new-password" />
            <x-input-error class="mt-2" :messages="$errors->updatePassword->get('password_confirmation')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button class="bg-black hover:bg-gray-800 text-white px-8 py-3 rounded-none tracking-widest text-xs transition">
                更新する
            </x-primary-button>

            @if (session('status') === 'password-updated')
            <p
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 2000)"
                class="text-sm text-gray-500">更新しました。</p>
            @endif
        </div>
    </form>
</section>