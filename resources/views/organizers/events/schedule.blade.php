<x-app-layout>
     <x-slot name="header">
          <h2 class="font-semibold text-xl text-gray-800 leading-tight">
               {{ __('Book a Concert') }}
          </h2>
     </x-slot>

     <div class="py-12">
          <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
               <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                         <div class="flex justify-between">
                              <h1 class="text-xl font-bold">{{ __("Select Dates") }}</h1>
                              <div class="flex gap-4">
                                   <div class="flex gap-2">
                                        <div class="w-8 h-[2px] bg-red-500 my-auto"></div>
                                        Already Booked
                                   </div>
                                   <div class="flex gap-2">
                                        <div class="w-8 h-[2px] bg-green-500 my-auto"></div>
                                        You Booked
                                   </div>
                                   <div class="flex gap-2">
                                        <div class="w-8 h-[2px] bg-purple-500 my-auto"></div>
                                        Selected
                                   </div>
                              </div>
                         </div>
                         <livewire:organizer.events.schedule />
                    </div>
               </div>

          </div>
     </div> 
</x-app-layout>