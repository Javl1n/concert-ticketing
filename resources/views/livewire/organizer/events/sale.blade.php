<?php

use function Livewire\Volt\{state};

use Illuminate\Support\Carbon;

state([
    'concert',
    'total' => function () {
        $total = 0.00;
        foreach ($this->concert->tickets as $ticket) {
            if($ticket->user !== null && $ticket->approved) {
                $total += $ticket->vip ? $this->concert->vip_price : $this->concert->general_price;
            }
        }
        return $total;
    } 
]);

?>

<div class="bg-white p-8 rounded-xl shadow col-span-2 flex flex-col">
    <div class="flex justify-between">
        <h2 class="text-xl font-semibold">Ticket Sales</h2>
        <a href="{{ route('organizer.sales.print', ['concert' => $concert]) }}">
            <x-primary-button>Print Sales</x-primary-button>
        </a>
    </div>
    <div class="border p-2 mt-2 flex-1">
        <div class="grid grid-cols-12 font-bold border-b pb-2">
            <h1 class="col-span-1 text-center">Row</h1>
            <h1 class="col-span-2 text-center">Column</h1>
            <h1 class="col-span-3 text-center">Ticket Type</h1>
            <h1 class="col-span-3 text-center">Custumer</h1>
            <h1 class="col-span-3 text-center">Status</h1>
        </div>
        <div class="h-72 overflow-auto">
            @foreach ($concert->tickets as $ticket)
                @livewire('organizer/events/ticket-sale', ['ticket' => $ticket], key($ticket->id))
            @endforeach
        </div>
    </div>
    <span class="mt-2 text-sm font-normal text-gray-500">Total Sale: PHP <span>{{ $total }}</span></span>
    {{-- <x-modal name="print-area" maxWidth="4xl" :show="true" class="">
        <div x-data="{
            printDiv() {
                var printContents = this.$refs.container.innerHTML;
                var originalContents = document.body.innerHTML;
                document.body.innerHTML = print
            }
        }" class="p-6">
            <div id="printableArea">
                <div class="text-lg font-bold">
                    {{ $concert->name }}
                </div>
                <div class="border rounded p-2 mt-2 flex-1">
                    <div class="grid grid-cols-12 font-bold border-b pb-2">
                        <h1 class="col-span-4">Customer</h1>
                        <h1 class="col-span-5 text-center">Date Paid</h1>
                        <h1 class="col-span-3 text-center">Amount</h1>
                    </div>
                    <div class="min-h-96">
                        @foreach ($concert->tickets->whereNotNull("user") as $ticket)
                            <div class="grid grid-cols-12 py-1">
                                <h1 class="col-span-4">{{ $ticket->user->name }}</h1>
                                <h1 class="col-span-5 text-center">{{ Carbon::create($ticket->bought_at)->format("F j, Y g:i:s A") }}</h1>
                                <h1 class="col-span-3 text-center">PHP {{ $ticket->vip ? $concert->vip_price : $concert->general_price }}</h1>
                            </div>
                        @endforeach
                    </div>
                    <div class="grid grid-cols-12 font-bold mt-2 border-t pt-4 pb-2">
                        <h1 class="col-span-9">Total Amount</h1>
                        <h1 class="col-span-3 text-center">PHP {{ $total }}</h1>
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-2 mt-6">
                <x-primary-button x-on:click='print'>
                    Print
                </x-primary-button>
            </div>
        </div>
    
    </x-modal> --}}
</div>