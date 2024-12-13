<?php

use function Livewire\Volt\{state};

use Illuminate\Support\Carbon;

state([
    'ticket',
]);

$approve = function () {
    if ($this->ticket->user !== null) {
        $this->ticket->update([
            'approved' => true
        ]);
        // $this->dispatch('close');
    }
};

$disapprove = function () {
    if ($this->ticket->user !== null) {
        $this->ticket->update([
            'approved' => false
        ]);
        // $this->dispatch('close', name: $this->ticket->id);
    }
}

?>

<div class="">
    <div x-on:click="$dispatch('open-modal', '{{ $ticket->id }}')" class="grid grid-cols-12 py-1 {{ $this->ticket->user !== null ? 'hover:bg-gray-50 cursor-pointer' : 'text-gray-400' }}" wire:poll>
        <h1 class="col-span-1 text-center">{{ $ticket->row + 1 }}</h1>
        <h1 class="col-span-2 text-center">{{ $ticket->column + 1 }}</h1>
        <h1 class="col-span-3 text-center">{{ $ticket->vip ? "VIP" : "General Admission" }}</h1>
        <h1 class="col-span-3 text-center">{{ $ticket->user ? $ticket->user->name : "Not sold" }}</h1>
        <h1 class="col-span-3 text-center">{{ 
            !$this->ticket->user ? "Not sold" : 
                ($this->ticket->approved === null ? "Pending" : 
                    ($this->ticket->approved ? "Accepted" : "Rejected")) }}</h1>
    </div> 
    @if ($ticket->user)
        <x-modal name="{{ $ticket->id }}" :show="false" class="">
            <div class="p-6">
                <div class="text-sm">Name:</div>
                <div class="text-lg font-bold">
                    {{ $ticket->user->name }}
                </div>
                <div class="mt-2 flex gap-6">
                    <div class="">
                        <div class="text-sm">Date Bought:</div>
                        <div class="text-lg font-bold">
                            {{ Carbon::create($ticket->bought_at)->format('F j, Y \a\t g:i a') }}
                        </div>
                    </div>
                    <div class="">
                        <div class="text-sm">Ticket Type:</div>
                        <div class="text-lg font-bold">
                            {{ $ticket->vip ? 'VIP' : 'General Admission' }} (PHP {{ $ticket->vip ? $ticket->concert->vip_price : $ticket->concert->general_price }})
                        </div>
                    </div>
                </div>
                <div class="text-sm mt-2">Receipt:</div>
                <img class="max-h-[50vh] mx-auto border rounded p-2" src="{{ asset($ticket->receipt->url) }}" alt="">
                <div class="flex justify-end gap-2 mt-6">
                    @if($this->ticket->approved)
                        <button disabled class="inline-flex items-center px-4 py-2 bg-white border rounded-md font-semibold text-xs text-gray-500 uppercase tracking-widest transition ease-in-out duration-150">
                            accepted
                        </button>
                    @else
                        <button wire:click='disapprove' x-on:click="$dispatch('close')" class="inline-flex items-center px-4 py-2 bg-white border hover:text-gray-500 rounded-md font-semibold text-xs text-slate-900 uppercase tracking-widest transition ease-in-out duration-150">
                            Disapprove
                        </button>
                        <x-primary-button wire:click='approve'>
                            Approve
                        </x-primary-button>
                    @endif
                </div>
            </div>
        </x-modal>
    @endif
</div>
