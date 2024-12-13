<x-app-layout>
     <x-slot name="header">
     <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          {{ __($concert->name) }}
     </h2>
     </x-slot>

     <div class="py-8">
          <div class="max-w-7xl mx-auto px-8">
               <div class="bg-white rounded-xl shadow">
                    <img src="{{ asset($concert->image->url) }}" class="w-full object-contain rounded-t-lg">
                    <div class="p-8">
                         <div class="">
                              <h2 class="text-xl font-semibold">Description</h2>
                              <p class="text-gray-700 mt-2">{{ $concert->description }}</p>
                         </div>
                         <div class="grid grid-cols-3 gap-6 mt-4">
                              <div>
                                   <h2 class="text-xl font-semibold mb-4">Concert Starts</h2>
                                   <div class="flex mb-2">
                                        <span class="text-gray-600 w-12">From:</span>
                                        <span class="text-gray-800">{{ $carbon->create($concert->concert_start)->format('F j, Y h:i A') }}</span>
                                   </div>
                                   <div class="flex">
                                        <span class="text-gray-600 w-12">To:</span>
                                        <span class="text-gray-800">{{ $carbon->create($concert->concert_end)->format('F j, Y h:i A') }}</span>
                                   </div>
                              </div>
                              <div>
                                   <h2 class="text-xl font-semibold mb-4">Pricing</h2>
                                   <div class="flex mb-2">
                                        <span class="text-gray-600 w-12">GA:</span>
                                        <span class="text-gray-800 font-bold">PHP {{ $concert->general_price }}</span>
                                   </div>
                                   <div class="flex">
                                        <span class="text-gray-600 w-12">VIP:</span>
                                        <span class="text-purple-500 font-bold">PHP {{ $concert->vip_price }}</span>
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>
               {{-- <div class="grid grid-cols-3 gap-4 mt-4">
                    <div class="bg-white p-8 rounded-xl shadow text-center">
                         <h2 class="text-xl font-semibold">Payment Method</h2>
                         <p class="my-2">Gcash: {{ $concert->gcash }}</p>
                         <img src="{{ asset($concert->qrImage->url) }}" class="border mt-4 " alt="">
                    </div>
                    <div class="bg-white p-8 rounded-xl shadow col-span-2 flex flex-col">
                         <h2 class="text-xl font-semibold">Ticket Sales</h2>
                         <div class="border p-2 mt-2 flex-1">
                              <div class="grid grid-cols-12 font-bold border-b pb-2">
                              <h1 class="col-span-1 text-center">Row</h1>
                              <h1 class="col-span-2 text-center">Column</h1>
                              <h1 class="col-span-3">Ticket Type</h1>
                              <h1 class="col-span-3">Costumer</h1>
                              <h1 class="col-span-3">Receipt Detail</h1>
                              </div>
                              <div class="h-80 overflow-auto">
                              @foreach ($concert->tickets as $ticket)
                                   @livewire('organizer/events/ticket-sale', ['ticket' => $ticket], key($ticket->id))
                              @endforeach
                              </div>
                         </div>
                    </div>
               </div> --}}
               <div class="bg-white p-8 mt-4 rounded-xl shadow">
                    <h2 class="text-xl font-semibold">Available Seats</h2>
                    <div>
                         @for($row = 0; $row < 9; $row++)
                              <div class="flex gap-2 {{ $row === 4 ? 'mt-10' : 'mt-4' }}">
                              @for($column = 0; $column < 20; $column++)
                                   <div class="{{
                                        $concert->tickets
                                             ->where('row', $row)
                                             ->where('column', $column)
                                             ->first()->vip
                                        ? 'bg-purple-500'
                                        : 'bg-gray-600'
                                   }} w-full h-10  rounded {{ $column === 10 ? 'ms-10' : '' }}">
                                   </div>
                                   {{-- <button wire:click="changeSeatState({{ $row }}, {{ $column }})" class="{{
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
                                   </button> --}}
                                   {{-- <div class="{{ $concert->where }} transition w-full h-10 bg-gray-600 rounded {{ $column === 10 ? 'ms-10' : '' }}"> --}}

                                   {{-- </div> --}}
                              @endfor
                              </div>
                         @endfor
                    </div>
               </div>
               <div class="flex justify-end gap-2 mt-6">
                    {{-- <a href="{{ route('organizer.concerts.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-transparent rounded-md font-semibold text-xs text-slate-900 uppercase tracking-widest transition ease-in-out duration-150">Cancel</a> --}}
                    <form action="{{ route('user.concert.order', [$concert]) }}">
                         <x-primary-button>Order Ticket</x-primary-button>
                    </form>
                </div>
          </div>
     </div> 
</x-app-layout>