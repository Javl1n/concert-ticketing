<x-app-layout>
     <x-slot name="header">
          <h2 class="font-semibold text-xl text-gray-800 leading-tight">
               {{ __('Your Tickets') }}
          </h2>
     </x-slot>

     <div class="py-12">
          <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
               <div class="bg-white rounded-xl shadow p-8">
                    <div class="grid grid-cols-12 font-bold border-b">
                         <h1 class="col-span-2">Concert</h1>
                         <h1 class="col-span-2">Date</h1>
                         <h1 class="col-span-2">Row</h1>
                         <h1 class="col-span-2">Column</h1>
                         <h1 class="col-span-2">Price</h1>
                         <h1 class="col-span-2">Status</h1>
                    </div>
                    @foreach ($tickets as $ticket)
                         <div class="grid grid-cols-12 my-2">
                              <h1 class="col-span-2">{{ $ticket->concert->name }}</h1>
                              <h1 class="col-span-2">{{ $carbon->create($ticket->concert->concert_start)->format('F d, o') }}</h1>
                              <h1 class="col-span-2">{{ $ticket->row }}</h1>
                              <h1 class="col-span-2">{{ $ticket->column }}</h1>
                              <h1 class="col-span-2">PHP {{ number_format($ticket->vip ? $ticket->concert->vip_price : $ticket->concert->general_price, 2) }}</h1>
                              @if ($ticket->approved !== null)
                                   @if ($ticket->approved)
                                        <h1 class="col-span-2 text-green-600">Approved</h1>
                                   @else
                                        <a href="" class="col-span-2 text-red-500">Disapproved</a>
                                   @endif
                              @else
                                   <h1 class="col-span-2">Pending</h1> 
                              @endif
                         </div> 
                    @endforeach
               </div>
          </div>
     </div>
</x-app-layout>
