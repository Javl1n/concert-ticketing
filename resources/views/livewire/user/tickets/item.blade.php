<?php

use function Livewire\Volt\{state};

use Illuminate\Support\Carbon;

state([
    'ticket'
]);



?>


<div class="" wire:poll>
    <div class="grid grid-cols-12 my-2">
        <h1 class="col-span-2">{{ $this->ticket->concert->name }}</h1>
        <h1 class="col-span-2">{{ Carbon::create($this->ticket->concert->concert_start)->format('F d, o') }}</h1>
        <h1 class="col-span-2 text-center">{{ $this->ticket->row }}</h1>
        <h1 class="col-span-2 text-center">{{ $this->ticket->column }}</h1>
        <h1 class="col-span-2 text-center">PHP {{ number_format($this->ticket->vip ? $this->ticket->concert->vip_price : $this->ticket->concert->general_price, 2) }}</h1>
        @if ($this->ticket->approved !== null)
             @if ($this->ticket->approved)
                <h1 x-on:click.prevent="$dispatch('open-modal', '{{ $this->ticket->id }}')" class="cursor-pointer col-span-2 text-green-600 text-center">Approved</h1>
             @else
                <a href="{{ route("user.ticket.delete", ['ticket' => $this->ticket->id]) }}" class="col-span-2 text-red-500 text-center">Disapproved</a>
             @endif
        @else
             <h1 class="col-span-2 text-center">Pending</h1>
        @endif
    </div>
    @if ($this->ticket->approved)
        <x-modal name="{{ $ticket->id }}" :show="false" class="">
            <div class="p-6">
                <div class="border rounded p-4 flex gap-8">
                    <div class="flex-1 flex flex-col justify-center">
                        <div class="text-3xl font-extrabold text-wrap">
                            {{ $ticket->concert->name }}
                        </div>
                        {{-- <div class="">
                            {{ Carbon::create($ticket->concert->concert_start)->format('F d, Y') }}
                        </div> --}}
                    </div>
                    <div class="">
                        <div class="text-sm font-bold">Start:</div>
                        <div class="text-lg">
                            {{ Carbon::create($ticket->concert->concert_start)->format('F d, Y') }}
                        </div>
                        <div class="text-lg">
                            {{ Carbon::create($ticket->concert->concert_start)->format('\\a\\t g:i:s A') }}
                        </div>
                        <div class="text-sm font-bold mt-4">End:</div>
                        <div class="text-lg">
                            {{ Carbon::create($ticket->concert->concert_end)->format('F d, Y') }}
                        </div>
                        <div class="text-lg">
                            {{ Carbon::create($ticket->concert->concert_end)->format('\\a\\t g:i:s A') }}
                        </div>
                    </div>
                    <div class="-rotate-90 border-t border-dashed">
                        <div class="pt-6">
                            <div class="mt-4 text-sm font-bold text-center">{{ $ticket->concert->id }} - {{ $ticket->id }} - {{ $ticket->row }} - {{ $ticket->column }}</div>
                            <div class="flex gap-7">
                                <div class="text-center">
                                    <div class="text-sm">Column</div>
                                    <div class="text-lg">{{ $ticket->column }}</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-sm">Row</div>
                                    <div class="text-lg">{{ $ticket->row }}</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-sm">Ticket</div>
                                    <div class="text-lg">{{ $ticket->vip ? "VIP" : "GA" }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-2 text-sm text-gray-400">Screenshot this ticket</div>
            </div>
        </x-modal>
    @endif
</div>
