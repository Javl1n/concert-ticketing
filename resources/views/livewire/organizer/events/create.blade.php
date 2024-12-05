<?php

use function Livewire\Volt\{state, usesFileUploads};
use Illuminate\Support\Carbon;

usesFileUploads();

state([
    'firstDay',
    'lastDay',
    'concertPhoto',
    'name',
    'description',
    'concertDayStart' => fn() => $this->firstDay,
    'concertStart' => [
        'hour' => 11,
        'minute' => 0,
        'time' => 'AM'
    ],
    'concertDayEnd' => fn() => $this->lastDay,
    'concertEnd' => [
        'hour' => 12,
        'minute' => 0,
        'time' => 'PM'
    ],
    'tickets' => collect([
        'general' => 50.00,
        'vip' => 100.00
    ]),
    'gcashName',
    'gcashNumber',
    'gcashPhoto',
    'vipSeats' => function () {
        $collection = collect([]);
        for($row  = 0; $row < 2; $row++) {
            for($column  = 0; $column < 20; $column++) {
                $collection->push(['row' => $row, 'column' => $column]);
            }
        }
        return $collection;
    },
]);

$selectDayStart = function ($day) {
    // dd(1);
    $this->concertDayStart = Carbon::create($this->firstDay->year, $this->firstDay->month, $day);
};

$selectDayEnd = function ($day) {
    // dd(1);
    $this->concertDayEnd = Carbon::create($this->firstDay->year, $this->firstDay->month, $day);
};

$changeStartHour = function (int $hour) {
    if ($hour > 12) {
        $this->concertStart['hour'] = 1;
    } 

    if ($hour < 1) {
        $this->concertStart['hour'] = 12;
    }
};

$changeStartMinute = function (int $minute) {
    if ($minute > 59) {
        $this->concertStart['minute'] = 0;
    } 

    if ($minute < 0) {
        $this->concertStart['minute'] = 59;
    }
};

$changeEndHour = function (int $hour) {
    if ($hour > 12) {
        $this->concertEnd['hour'] = 1;
    } 

    if ($hour < 1) {
        $this->concertEnd['hour'] = 12;
    }
};

$changeEndMinute = function (int $minute) {
    if ($minute > 59) {
        $this->concertEnd['minute'] = 0;
    } 

    if ($minute < 0) {
        $this->concertEnd['minute'] = 59;
    }
};

$selectStartTime = fn ($time) => $this->concertStart['time'] = $time;
$selectEndTime = fn ($time) => $this->concertEnd['time'] = $time;

$changeSeatState = function ($row, $column) {
    if ($this->vipSeats->map(function ($value) use ($row, $column) {
        if ($value['row'] === $row && $value['column'] === $column) {
            return true;
        } else {
            return false;
        }
    })->search(true) !== false) {
        $this->vipSeats = $this->vipSeats->reject(function ($value, $key) use ($row, $column) {
            return $value['row'] === $row && $value['column'] === $column;
        });
    } else {
        $this->vipSeats->push(["row" => $row, "column" => $column]);
    }
};

$save = function () {
    // dd(auth()->user());
    $this->validate([
        'concertPhoto' => 'required|mimes:png,jpg',
        'gcashPhoto' => 'required|mimes:png,jpg',
        'name' => 'required',
        'description' => 'required',
        'concertStart.hour' => 'required|numeric|lte:12|gte:1',
        'concertEnd.hour' => 'required|numeric|lte:12|gte:1',
        'concertStart.minute' => 'required|numeric|lt:60|gte:0',
        'concertEnd.minute' => 'required|numeric|lt:60|gte:0',
        'tickets.general' => 'required|numeric|gt:0',
        'tickets.vip' => 'required|numeric|gt:0',
        'gcashName' => 'required',
        'gcashNumber' => 'required',
    ]);
    
    $concert = auth()->user()->concerts()->create([
        'name' => $this->name,
        'description' => $this->description,
        'reservation_start' => $this->firstDay,
        'reservation_end' => $this->lastDay,
        'concert_start' => Carbon::create(
            $this->concertDayStart->year, 
            $this->concertDayStart->month, 
            $this->concertDayStart->day,
            $this->concertStart['time'] !== 'AM' && $this->concertStart['hour'] < 12 ? $this->concertStart['hour'] + 12 : $this->concertStart['hour'],
            $this->concertStart['minute'],
            0
        ),
        'concert_end' => Carbon::create(
            $this->concertDayEnd->year, 
            $this->concertDayEnd->month, 
            $this->concertDayEnd->day,
            $this->concertEnd['time'] !== 'AM' && $this->concertEnd['hour'] < 12 ? $this->concertEnd['hour'] + 12 : $this->concertEnd['hour'],
            $this->concertEnd['minute'],
            0
        ),
        'vip_price' => $this->tickets['vip'],
        'general_price' => $this->tickets['general'],
        'gcash_name' => $this->gcashName,
        'gcash_number' => $this->gcashNumber,
    ]);

    $image = $concert->image()->create([
        'url' => $this->concertPhoto->storePublicly('photos'),
    ]);
    
    $concert->qrImage()->create([
        'url' => $this->gcashPhoto->storePublicly('photos'),
    ]);

    for($row = 0; $row < 9; $row++) {
        for ($column = 0; $column < 20; $column++) {
            $vip = $this->vipSeats->map(function ($value) use ($row, $column) {
                if ($value['row'] === $row && $value['column'] === $column) {
                    return true;
                } else {
                    return false;
                }
            })->search(true) !== false;

            $concert->tickets()->create([
                'row' => $row,
                'column' => $column,
                'vip' => $vip
            ]);
        };
    };

    
    // dd($concert);
    return $this->redirect(route('organizer.concerts.index', absolute: false), navigate: true);
};

?>
<div class="">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="py-6 px-10 text-gray-900">
            <div>
                <h1 class="text-xl font-bold">Booking Form</h1>

                {{-- Concert Photo --}}
                <div class="mt-4">
                    <x-input-label>Concert Photo</x-input-label>
                    <div class="flex items-center justify-center w-full">
                        <label for="concert-photo" class="flex flex-col items-center justify-center w-full h-96 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer  hover:bg-gray-50 transition">
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
                            <input wire:model='concertPhoto' id="concert-photo" type="file" class="hidden" />
                        </label>
                    </div>
                    <x-input-error :messages="$errors->get('concertPhoto')" class="mt-2" />
                </div>

                {{-- Name --}}
                <div class="mt-4">
                    <x-input-label>Concert Name</x-input-label>
                    <x-text-input wire:model="name" class="block mt-1 w-full" type="text" required />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                {{-- Description --}}
                <div class="mt-4">
                    <x-input-label>Description</x-input-label>
                    <textarea rows="5" wire:model="description" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-violet-600 rounded-md shadow-sm" ></textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

                {{-- Date --}}
                <div class="mt-4">
                    <x-input-label>Date</x-input-label>
                    <div class="flex gap-2">
                        <div class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm">{{ $this->firstDay->format('F j, Y') }}</div>
                        <div class="my-auto text-lg font-bold">-</div>
                        <div class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm">{{ $this->lastDay->format('F j, Y') }}</div>
                    </div>
                    {{-- <x-input-error :messages="$errors->get('name')" class="mt-2" /> --}}
                </div>

                {{-- Time --}}
                <div class="mt-4">
                    <x-input-label>Time of the Concert</x-input-label>

                    <div class="flex gap-2">
                        {{-- From --}}
                        <div class="border p-2 border-gray-300 rounded-md shadow-sm w-full mt-1 flex">
                            {{-- Date --}}
                            <x-dropdown contentClasses="max-h-32 bg-white overflow-auto" align="left" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                        <div>{{ $this->concertDayStart->format('F j, Y') }}</div>
            
                                        <div class="ms-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>
            
                                <x-slot name="content">
                                    @for ($day = $this->firstDay->day; $day <=  $this->concertDayEnd->day; $day++)
                                        <button wire:click="selectDayStart({{ $day }})" class="w-full text-start">
                                            <x-dropdown-link>
                                                {{ Carbon::create($this->firstDay->year, $this->firstDay->month, $day)->format('F j, Y') }}
                                            </x-dropdown-link>
                                        </button>
                                    @endfor
                                    
                                </x-slot>
                            </x-dropdown>

                            <div class="w-px bg-gray-300 my-1 mx-4"></div>

                            <div class="flex gap-2 flex-1">
                                <x-text-input type="number" wire:model='concertStart.hour' wire:change='changeStartHour($event.target.value)' class="w-12 py-0 px-1 border-gray-300 ring-0 text-center rounded" />

                                <div>:</div>

                                <x-text-input type="number" wire:model='concertStart.minute' wire:change='changeStartMinute($event.target.value)' class="w-12 py-0 px-1 border-gray-300 rounded ring-0 text-center" />

                                <x-dropdown contentClasses=" bg-white overflow-auto" align="left" width="20">
                                    <x-slot name="trigger">
                                        <button class="inline-flex items-center border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                            <div>{{ $this->concertStart['time'] }}</div>
                                            <div class="ms-1">
                                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </button>
                                    </x-slot>
                
                                    <x-slot name="content">
                                        <button wire:click="selectStartTime('AM')" class="w-full text-start">
                                            <x-dropdown-link>
                                                AM
                                            </x-dropdown-link>
                                        </button>
                                        <button wire:click="selectStartTime('PM')" class="w-full text-start">
                                            <x-dropdown-link>
                                                PM  
                                            </x-dropdown-link>
                                        </button>
                                    </x-slot>
                                </x-dropdown>
                            </div>
                        </div>

                        <div class="my-auto text-lg font-bold">-</div>

                        {{-- To --}}
                        <div class="border p-2 border-gray-300 rounded-md shadow-sm w-full mt-1 flex">
                            <x-dropdown contentClasses="max-h-32 bg-white overflow-auto" align="left" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                        <div>{{ $this->concertDayEnd->format('F j, Y') }}</div>
                                        <div class="ms-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    @for ($day = $this->concertDayStart->day; $day <= $this->lastDay->day; $day++)
                                        <button wire:click="selectDayEnd({{ $day }})" class="w-full text-start">
                                            <x-dropdown-link>
                                                {{ Carbon::create($this->firstDay->year, $this->firstDay->month, $day)->format('F j, Y') }}
                                            </x-dropdown-link>
                                        </button>
                                    @endfor
                                </x-slot>
                            </x-dropdown>

                            <div class="w-px bg-gray-300 my-1 mx-4"></div>

                            <div class="flex gap-2 flex-1">
                                <x-text-input type="number" wire:model='concertEnd.hour' wire:change='changeEndHour($event.target.value)' class="w-12 py-0 px-1 border-gray-300 ring-0 text-center rounded" placeholder="00" />

                                <div>:</div>

                                <x-text-input type="number" wire:model='concertEnd.minute' wire:change='changeEndMinute($event.target.value)' class="w-12 py-0 px-1 border-gray-300 rounded ring-0 text-center" placeholder="00" />

                                <x-dropdown contentClasses=" bg-white overflow-auto" align="left" width="20">
                                    <x-slot name="trigger">
                                        <button class="inline-flex items-center border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                            <div>{{ $this->concertEnd['time'] }}</div>

                                            <div class="ms-1">
                                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </button>
                                    </x-slot>
                
                                    <x-slot name="content">
                                        <button wire:click="selectEndTime('AM')" class="w-full text-start">
                                            <x-dropdown-link>
                                                AM
                                            </x-dropdown-link>
                                        </button>
                                        <button wire:click="selectEndTime('PM')" class="w-full text-start">
                                            <x-dropdown-link>
                                                PM  
                                            </x-dropdown-link>
                                        </button>
                                    </x-slot>
                                </x-dropdown>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-6 mt-2">
                        <div class="w-full">
                            <x-input-error :messages="$errors->get('concertStart.hour')" class="mt-2" />
                            <x-input-error :messages="$errors->get('concertStart.minute')" class="mt-2" />
                        </div>
                        <div class="w-full">
                            <x-input-error :messages="$errors->get('concertEnd.hour')" class="mt-2" />
                            <x-input-error :messages="$errors->get('concertEnd.minute')" class="mt-2" />
                        </div>
                    </div>
                    <span class="text-xs text-gray-500">This is how concert goers know when to come</span>
                </div>

                {{-- Ticket Prices --}}
                <div class="mt-4">
                    <x-input-label>Ticket Prices</x-input-label>
                    <div class="flex gap-5">
                        <div class="border p-2 border-gray-300 rounded-md shadow-sm w-full mt-1 flex gap-4">
                            {{-- <input type="number" wire:model='concertStart.hour' class="w-12 py-0 px-1 border-gray-300 ring-0 text-center rounded" placeholder="00"> --}}
                            <div class="w-20 text-gray-600 font-light my-auto">General Admission</div>
                            <x-text-input wire:model="tickets.general" class="block flex-1 px-1 text-end text-lg py-2" type="number" required />
                            <div class="my-auto me-4 ms-2 font-bold text-gray-600">&#8369;</div>
                        </div>
                        <div class="border p-2 border-gray-300 rounded-md shadow-sm w-full mt-1 flex gap-4">
                            {{-- <input type="number" wire:model='concertStart.hour' class="w-12 py-0 px-1 border-gray-300 ring-0 text-center rounded" placeholder="00"> --}}
                            <div class="w-16 text-center text-xl  text-gray-600 font-light my-auto">VIP</div>
                            <x-text-input wire:model="tickets.vip" class="block flex-1 px-1 text-end text-lg py-2" type="number" required />
                            <div class="my-auto me-4 ms-2 font-bold text-gray-600">&#8369;</div>
                        </div>
                    </div>
                    <div class="flex gap-5">
                        <x-input-error :messages="$errors->get('tickets.general')" class="mt-2 w-full" />
                        <x-input-error :messages="$errors->get('tickets.vip')" class="mt-2 w-full" />
                    </div>
                    {{-- <x-input-error :messages="$errors->get('name')" class="mt-2" /> --}}
                </div>

                {{-- Gcash --}}
                <div class="mt-4">
                    <x-input-label>Gcash Name</x-input-label>
                    <x-text-input wire:model="gcashName" class="block mt-1 w-full" type="text" required />
                    <x-input-error :messages="$errors->get('gcashName')" class="mt-2" />
                </div>
                <div class="mt-4">
                    <x-input-label>Gcash Number</x-input-label>
                    <x-text-input wire:model="gcashNumber" class="block mt-1 w-full" type="text" required />
                    <x-input-error :messages="$errors->get('gcashNumber')" class="mt-2" />
                </div>

                {{-- QR --}}
                <div class="mt-4">
                    <x-input-label>Gcash QR</x-input-label>
                    <div class="flex items-center justify-center w-full">
                        <label for="gcash-photo" class="flex flex-col items-center justify-center w-full h-96 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer  hover:bg-gray-50 transition">
                            @if ($this->gcashPhoto)
                                <img class="h-full" src="{{ $this->gcashPhoto->temporaryUrl() }}" />
                            @else
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                    {{-- <p class="text-xs te   xt-gray-500 dark:text-gray-400">SVG, PNG, JPG or GIF (MAX. 800x400px)</p> --}}
                                </div>
                            @endif
                            <input wire:model='gcashPhoto' id="gcash-photo" type="file" class="hidden" />
                        </label>
                    </div>
                    <x-input-error :messages="$errors->get('gcashPhoto')" class="mt-2" />
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
                         <div class="w-8 h-[3px] bg-gray-600 my-auto"></div>
                         <p>General Admission</p>
                    </div>
                    <div class="flex gap-2">
                        <div class="w-8 h-[3px] bg-purple-500 my-auto"></div>
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
                            }} transition w-full h-10 bg-gray-600 rounded {{ $column === 10 ? 'ms-10' : '' }}">
                            </button>
                        @endfor
                    </div>
                @endfor
            </div>
        </div>
    </div>
    <div class="flex justify-end gap-2 mt-6">
        <a href="{{ route('organizer.concerts.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-transparent rounded-md font-semibold text-xs text-slate-900 uppercase tracking-widest transition ease-in-out duration-150">Cancel</a>
        <x-primary-button wire:click='save'>Next</x-primary-button>
    </div>
</div>
