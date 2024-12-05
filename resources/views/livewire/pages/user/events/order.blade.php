<?php

use function Livewire\Volt\{state, usesFileUploads};

use App\Models\Concert;

usesFileUploads();

state([
    'concert',
    'phase' => 0,
    'selectedSeats' => collect([
        // ["row" => 0, "column" => 0],
    ]),
    'balance' => 0.00,
    "generalSeats" => 0,
    "vipSeats" => 0,
    "photo"
]);

$resetSeats = function () {
    $this->selectedSeats = collect([]);
    $this->vipSeats = 0;
    $this->generalSeats = 0;
    $this->balance = 0.00;
};

$selectSeat = function ($row, $column) {
    $ticket = $this->concert->tickets->where("row", $row)->where("column", $column)->first();
    if($this->selectedSeats->where("row", $row)->where("column", $column)->first()) {
        $this->selectedSeats = $this->selectedSeats->reject(function ($value, $key) use ($row, $column) {
            return $value['row'] === $row && $value['column'] === $column;
        });
        $ticket->vip ? $this->vipSeats-- : $this->generalSeats--;
        $ticket->vip ? $this->balance -= $this->concert->vip_price : $this->balance -= $this->concert->general_price;
    } else {
        $this->resetSeats();
        $this->selectedSeats->push(['row' => $row, 'column' => $column]);
        $ticket->vip ? $this->vipSeats++ : $this->generalSeats++;
        $ticket->vip ? $this->balance += $this->concert->vip_price : $this->balance+= $this->concert->general_price;
    }
};

$next = function () {
    if($this->balance > 0) {
        $this->phase = 1;
    }
};

$back = function () {
    $this->phase = 0;
};

$submit = function () {
    $this->validate([
        'photo' => 'required'
    ]);
    $seat =$this->selectedSeats->first();
    $ticket = $this->concert->tickets
        ->where("row", $seat['row'])
        ->where("column", $seat['column'])->first();
    $ticket->update([
        'user_id' => auth()->user()->id
    ]);

    $ticket->receipt()->create([
        'url' => $this->photo->storePublicly('photos')
    ]);

    return $this->redirect(route('user.ticket.index', absolute: false), navigate: true);
};

?>

<div class="py-8">
    <div class="max-w-7xl mx-auto px-8">
        @if ($this->phase === 1)
            <div class="bg-white p-8 rounded-xl shadow">
                <div class="">
                    <h1 class="text-xl font-semibold">Balance</h1>
                    <div class="flex gap-5 mt-4">
                        <h1 class="text-7xl"><span class="text-purple-500">PHP</span> {{ number_format($this->balance, 2) }}</h1>
                        <div class="text-3xl font-bold text-gray-500">
                            <h1>Row <span class="text-purple-800">{{ $this->selectedSeats->first()['row'] ?? "" }}</span></h1>
                            <h1>Column <span class="text-purple-500">{{ $this->selectedSeats->first()['column']  ?? "" }}</span></h1>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <h1 class="text-xl font-semibold">Pay Here</h1>
                    <p>Scan the QR code to pay through Gcash, after paying download the receipt</p>
                    <div class="border rounded mx-auto max-w-md p-4 mt-4">
                        <h1 class="font-bold text-lg text-center">{{ $concert->gcash_name }}</h1>
                        <p class="text-center font-bold">{{ $concert->gcash_number }}</p>
                        <img class="mx-auto" src="{{ asset($concert->qrImage->url) }}" alt="">
                    </div>
                </div>

                <div class="mt-4">
                    <h1 class="text-xl font-semibold">Receipt</h1>
                    <p>Upload Your Receipt Here.</p>

                    <div class="flex items-center justify-center w-full">
                        <label for="receipt-photo" class="flex flex-col items-center justify-center w-full h-96 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer  hover:bg-gray-50 transition">
                            @if ($this->photo)
                                <img class="h-full" src="{{ $this->photo->temporaryUrl() }}" />
                            @else
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                    {{-- <p class="text-xs te   xt-gray-500 dark:text-gray-400">SVG, PNG, JPG or GIF (MAX. 800x400px)</p> --}}
                                </div>
                            @endif
                            <input wire:model='photo' id="receipt-photo" type="file" class="hidden" />
                        </label>
                        <x-input-error :messages="$errors->get('photo')" class="mt-2" />
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-2 mt-6">
                <button wire:click='back' class="inline-flex items-center px-4 py-2 bg-white border border-transparent rounded-md font-semibold text-xs text-slate-900 uppercase tracking-widest transition ease-in-out duration-150">
                    Go Back
                </button>
                <x-primary-button wire:click='submit'>Next</x-primary-button>
            </div>
        @endif
        @if ($this->phase === 0)
            <div class="bg-white p-8 rounded-xl shadow">
                <div class="">
                    <h1 class="text-xl font-semibold">Balance</h1>
                    <div class="flex gap-5 mt-4">
                        <h1 class="text-7xl"><span class="text-purple-500">PHP</span> {{ number_format($this->balance, 2) }}</h1>
                        <div class="text-3xl font-bold text-gray-500">
                            <h1>Row <span class="text-purple-800">{{ $this->selectedSeats->first()['row'] ?? "" }}</span></h1>
                            <h1>Column <span class="text-purple-500">{{ $this->selectedSeats->first()['column'] ?? "" }}</span></h1>
                        </div>
                    </div>
                </div>
                <div class="flex justify-between mt-4">
                    <div class="flex my-auto gap-2">
                        <h2 class="text-xl font-semibold">Select Seating</h2>
                        @if ($balance > 0.00)
                            <button wire:click='resetSeats'><x-icons icon="arrow-counterclockwise" class="h-5 w-5 fill-gray-400" /></button>
                        @endif
                    </div>
                    <div class="">
                        <div class="flex gap-5 justify-end">
                            <div class="flex gap-2">
                                <div class="w-5 h-4 bg-purple-500 rounded my-auto"></div>
                                <h1 class="my-auto">VIP</h1>
                            </div>
                            <div class="flex gap-2">
                                <div class="w-5 h-4 bg-gray-600 rounded my-auto"></div>
                                <h1 class="my-auto">General Admission</h1>
                            </div>
                        </div>
                        <div class="flex gap-5 justify-end">
                            <div class="flex gap-2">
                                <div class="w-5 h-4 ring ring-red-400 rounded my-auto"></div>
                                <h1 class="my-auto">Already Taken</h1>
                            </div>
                            <div class="flex gap-2">
                                <div class="w-5 h-4 ring ring-purple-400 rounded my-auto"></div>
                                <h1 class="my-auto">Selected</h1>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    @for($row = 0; $row < 9; $row++)
                        <div class="flex gap-4 {{ $row === 4 ? 'mt-10' : 'mt-4' }}">
                            @for($column = 0; $column < 20; $column++)
                                @php
                                    $ticket = $concert->tickets
                                        ->where('row', $row)
                                        ->where('column', $column)
                                        ->first()
                                @endphp
                                <button {{ 
                                    $ticket->user
                                    ? 'disabled'
                                    : ''
                                }} wire:click='selectSeat({{ $row }}, {{ $column }})' class="{{
                                    $ticket->vip
                                    ? 'bg-purple-500'
                                    : 'bg-gray-600'
                                }} {{ 
                                    $ticket->user
                                    ? 'ring ring-offset-2 ring-red-400'
                                    // : 'ring ring-offset-2 ring-purple-400'
                                    : ''
                                }} {{ 
                                    $selectedSeats->where("row", $row)->where("column", $column)->first()
                                    ? 'ring ring-offset-2 ring-purple-400'
                                    : ''
                                }} transition w-full h-10  rounded {{ $column === 10 ? 'ms-5' : '' }}">
                                </button>
                            @endfor
                        </div>
                    @endfor
                </div>
            </div>
            <div class="flex justify-end gap-2 mt-6">
                <a href="{{ route('user.concert.show', [$concert]) }}" class="inline-flex items-center px-4 py-2 bg-white border border-transparent rounded-md font-semibold text-xs text-slate-900 uppercase tracking-widest transition ease-in-out duration-150">Cancel</a>
                <x-primary-button wire:click='next'>Next</x-primary-button>
            </div>
        @endif
    </div>
</div>
