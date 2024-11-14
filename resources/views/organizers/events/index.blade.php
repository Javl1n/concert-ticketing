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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
        </div>
    </div> 
</x-app-layout>