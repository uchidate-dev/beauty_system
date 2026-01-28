<x-app-layout>
    <x-slot name="header">
        <h2 class="font-light text-2xl text-gray-700 leading-tight tracking-widest">
            MY PAGE
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
            <div class="mb-6 bg-white border-l-4 border-gray-800 p-4 shadow-sm">
                <p class="text-sm text-gray-600 tracking-tighter">{{ session('success') }}</p>
            </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                <div class="flex justify-between items-end mb-8 border-b border-gray-100 pb-4">
                    <h3 class="text-lg font-light tracking-widest text-gray-800">RESERVATIONS</h3>
                    <a href="{{ route('reservations.index') }}" class="text-xs border border-gray-800 px-4 py-2 hover:bg-gray-800 hover:text-white transition-all duration-300">
                        NEW BOOKING
                    </a>
                </div>

                @if($reservations->isEmpty())
                <p class="text-gray-400 text-sm text-center py-12">現在、予定されている予約はありません。</p>
                @else
                <div class="space-y-6">
                    @foreach($reservations as $reservation)
                    <div class="group border border-gray-100 p-6 hover:border-gray-300 transition-all">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-xs text-gray-400 mb-1 tracking-tighter">
                                    {{ $reservation->reservation_date }} {{ substr($reservation->reservation_time, 0, 5) }}
                                </p>
                                <p class="text-lg font-medium text-gray-800 tracking-widest">
                                    {{ $reservation->staff->name }}
                                </p>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    @foreach($reservation->menus as $menu)
                                    <span class="text-[10px] bg-gray-50 text-gray-500 px-2 py-1 rounded">{{ $menu->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] tracking-widest uppercase text-gray-400">Status</span>
                                <p class="text-sm text-gray-600 mt-1">Confirmed</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>