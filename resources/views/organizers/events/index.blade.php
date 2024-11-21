<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Your Concerts') }}
        </h2>
        <a href="{{ route('organizer.concerts.create.schedule') }}" class="bg-violet-600 p-2 flex gap-2 rounded text-white absolute top-16 mt-4 right-80 me-6">
            <x-icons icon="plus-circle" class="h-6 w-6 my-auto" />
            Book a Concert
        </a>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-3 gap-4">
            @foreach ($concerts as $concert)
                <a href="{{ route('organizer.concerts.show', ['concert' => $concert->id]) }}"
                    class="flex flex-col bg-white border border-gray-200 rounded-lg shadow md:max-w-xl hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
                    <img class="w-full rounded-t-lg aspect-video object-center"
                        src="{{ asset($concert->image->url) }}" alt="">
                    <div class="p-4 leading-normal">
                        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">{{ $concert->name }}</h5>
                        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">{{ Str::limit($concert->description, 100) }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div> 
</x-app-layout>