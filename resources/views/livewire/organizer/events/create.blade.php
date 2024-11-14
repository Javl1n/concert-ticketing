<?php

use function Livewire\Volt\{state, usesFileUploads};

usesFileUploads();

state([
    'firstDay',
    'lastDay',
    'concertPhoto',
    'name',
    'description',
    'vipSeats' => collect([
        ['row' => 0, 'column' => 0],
    ]),
]);

$changeSeatState = function ($row, $column) {
    $this->vipSeats->push(["row" => $row, "column" => $column]);
    // dd($this->vipSeats);
};

?>
<div class="">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="py-6 px-10 text-gray-900">
            <div>
                <h1 class="text-xl font-bold">Booking Form</h1>
                <div class="mt-4">
                    <x-input-label>Concert Photo</x-input-label>
                    <div class="flex items-center justify-center w-full">
                        <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-96 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer  hover:bg-gray-50 transition">
                            @if ($this->concertPhoto)
                                <img class="h-full" src="{{ $this->concertPhoto->temporaryUrl() }}" />
                            @else
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                    {{-- <p class="text-xs te   xt-gray-500 dark:text-gray-400">SVG, PNG, JPG or GIF (MAX. 800x400px)</p> --}}
                                </div>
                            @endif
                            <input wire:model='concertPhoto' id="dropzone-file" type="file" class="hidden" />
                        </label>
                    </div>
                </div>
                <div class="mt-4">
                    <x-input-label>Concert Name</x-input-label>
                    <x-text-input wire:model="name" class="block mt-1 w-full" type="text" required />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>
                <div class="mt-4">
                    <x-input-label>Description</x-input-label>
                    <textarea rows="5" wire:model="description" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-violet-600 rounded-md shadow-sm" ></textarea>
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>
                <div class="mt-4">
                    <x-input-label>Date</x-input-label>
                    <div class="flex gap-2">
                        <div class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm">{{ $this->firstDay->format('F j, Y') }}</div>
                        <div class="my-auto text-lg font-bold">-</div>
                        <div class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm">{{ $this->lastDay->format('F j, Y') }}</div>
                    </div>
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>
                <div class="mt-4">
                    <x-input-label>Time of the Concert</x-input-label>
                    <div class="flex gap-2">
                        <x-text-input wire:model="name" placeholder="From" class="block mt-1 w-full" type="text" required />
                        <div class="my-auto text-lg font-bold">-</div>
                        <x-text-input wire:model="name" placeholder="To" class="block mt-1 w-full" type="text" required />
                    </div>
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    <span class="text-xs text-gray-500">This is how concert goers know when to come</span>
                </div>
            </div>
        </div>
    </div>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-12">
        <div class="py-6 px-10 text-gray-900">
            <div class="flex justify-between">
                <h1 class="text-xl font-bold">Seating</h1>
                <div class="flex gap-4">
                    <div class="flex gap-2">
                         <div class="w-8 h-[2px] bg-gray-600 my-auto"></div>
                         General Admission
                    </div>
                    <div class="flex gap-2">
                        <div class="w-8 h-[2px] bg-purple-500 my-auto"></div>
                        VIP
                    </div>
               </div>
            </div>
            <div>
                @for($row = 0; $row < 9; $row++)
                    <div class="flex gap-2 {{ $row === 4 ? 'mt-10' : 'mt-4' }}">
                        @for($column = 0; $column < 20; $column++)
                            <button wire:click="changeSeatState({{ $row }}, {{ $column }})" class="{{ 
                                $this->vipSeats->map(function ($value) use ($row, $column) {
                                    if ($value['row'] === $row && $value['column'] === $column) {
                                        return true;
                                    } else {
                                        return false;
                                    }
                                })->search(true) !== false
                                ? 'bg-purple-500' 
                                : 'hover:bg-purple-100'
                            }} transition w-full h-10 bg-gray-600 rounded {{ $column === 10 ? 'ms-10' : '' }}"></button>
                        @endfor
                    </div>
                @endfor
            </div>
        </div>
    </div>
    <div class="flex justify-end gap-2 mt-6">
        <a href="{{ route('organizer.concerts.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-transparent rounded-md font-semibold text-xs text-slate-900 uppercase tracking-widest transition ease-in-out duration-150">Cancel</a>
        <x-primary-button >Next</x-primary-button>
    </div>
</div>
