<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Welcome,') }} {{ auth()->user()->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        @php
            $firstConcert = $concerts->first();
        @endphp            
        <x-user.events.show-banner :concert=$firstConcert/>
        <div class="max-w-7xl mx-auto mt-6 sm:px-6 lg:px-8">
            <h1 class="font-bold text-3xl">New Concerts</h1>
            <div class="grid grid-cols-3 sm:grid-cols-2 md:grid-cols-3 gap-4 mt-5">
                @foreach ($concerts as $concert)
                    <x-user.events.show-card :concert=$concert />
                @endforeach
                <!-- Repeat the card structure as needed -->
            </div>
        </div>
    </div>
</x-app-layout>
