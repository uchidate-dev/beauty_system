<section class="space-y-6">
    <header>
        <h2 class="text-lg font-light tracking-widest text-gray-900">
            アカウント削除
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            一度アカウントを削除すると、予約履歴を含むすべてのデータが完全に消去されます。
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="bg-red-500 hover:bg-red-600 text-white font-medium px-6 py-3 rounded-none text-xs tracking-widest transition shadow-sm">アカウントを削除する
    </x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900">
                本当に削除しますか？
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                アカウントを削除するには、パスワードを入力してください。
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="パスワード" class="sr-only" />
                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-3/4 border-gray-200 focus:border-black focus:ring-0 rounded-none"
                    placeholder="パスワード" />
                <x-input-error class="mt-2" :messages="$errors->userDeletion->get('password')" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')" class="rounded-none text-xs tracking-widest">
                    キャンセル
                </x-secondary-button>

                <x-danger-button class="ms-3 bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-none text-xs tracking-widest transition">
                    削除を実行する
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>