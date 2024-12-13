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
                         <h1 class="col-span-2 text-center">Row</h1>
                         <h1 class="col-span-2 text-center">Column</h1>
                         <h1 class="col-span-2 text-center">Price</h1>
                         <h1 class="col-span-2 text-center">Status</h1>
                    </div>
                    @foreach ($tickets as $ticket)
                         @livewire('user.tickets.item', ['ticket' => $ticket])
                    @endforeach
               </div>
          </div>
     </div>
</x-app-layout>
