<section class="space-y-6">
    <header>
        <h2 class="text-lg font-light tracking-widest text-gray-900 uppercase">
            Delete Account <span class="text-xs opacity-50">/ アカウント削除</span>
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            一度アカウントを削除すると、すべてのデータが完全に消去されます。
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 px-6 py-3 rounded-none text-[10px] tracking-widest transition uppercase">
        Delete Account / アカウント削除
    </x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-8 bg-[#faf9f6]">
            @csrf
            @method('delete')

            <h2 class="text-lg font-light tracking-widest text-gray-900 uppercase">
                Are you sure? <span class="text-sm">/ 本当に削除しますか？</span>
            </h2>

            <p class="mt-4 text-sm text-gray-600">
                アカウントを完全に削除するには、パスワードを入力してください。
            </p>

            <div class="mt-8">
                <label class="block text-[10px] tracking-[0.2em] uppercase text-gray-600 mb-2 font-medium">
                    Password <span class="text-[8px] opacity-80">/ パスワード</span>
                </label>
                <input id="password" name="password" type="password"
                    class="w-3/4 border-0 border-b border-gray-300 focus:ring-0 focus:border-gray-800 transition-all p-0 pb-2 text-gray-900" placeholder="Password">
                <x-input-error class="mt-2" :messages="$errors->userDeletion->get('password')" />
            </div>

            <div class="mt-10 flex justify-end gap-4">
                <button type="button" x-on:click="$dispatch('close')" class="text-[10px] tracking-widest uppercase text-gray-400 hover:text-gray-800 transition">
                    Cancel / キャンセル
                </button>

                <x-danger-button class="bg-red-600 hover:bg-red-700 text-white px-8 py-3 rounded-none text-[10px] tracking-widest transition uppercase">
                    Delete / 削除を実行
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>