<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-light text-2xl text-gray-800 leading-tight tracking-widest">
                MY PROFILE <span class="text-xs block text-gray-400 mt-2">プロフィール設定</span>
            </h2>

            <a href="{{ route('dashboard') }}" class="text-xs tracking-widest text-gray-400 hover">
                BACK TO MYPAGE →
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-12">

            <div class="p-8 bg-white border border-gray-100 shadow-sm sm:rounded-lg">
                <div class="max-w-xl mx-auto">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-8 bg-white border border-gray-100 shadow-sm sm:rounded-lg">
                <div class="max-w-xl mx-auto">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-8 bg-gray-50 border border-gray-100 sm:rounded-lg opacity-80">
                <div class="max-w-xl mx-auto">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>