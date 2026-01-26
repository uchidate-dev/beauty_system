<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('success'))
                    <div class="mb-4 font-medium text-sm text-green-600 bg-green-100 p-4 rounded-lg border border-green-200 shadow-sm">
                        {{ session('success') }}
                    </div>
                    @endif
                    {{ __("You're logged in!") }}

                    <div class="mt-6">
                        <a href="{{ route('reservations.index') }}" class="bg-pink-500 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded">
                            新規予約はこちら
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</x-app-layout>