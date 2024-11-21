<x-app-layout>
     <x-slot name="header">
         <h2 class="font-semibold text-xl text-gray-800 leading-tight">
             {{ __($concert->name) }}
         </h2>
     </x-slot>
 
     <div class="py-8">
          <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
               <div class="bg-white p-4 rounded-xl shadow">
                    <img src="{{ asset($concert->image->url) }}" class=" object-contain rounded-lg">
                    <h2 class="text-xl font-semibold mt-4">Description</h2>
                    <p class="text-gray-700 mt-2">{{ $concert->description }}</p>
                    <div class="grid grid-cols-3 gap-6 mt-4">
                         <div>
                             <h2 class="text-xl font-semibold mb-4">Date</h2>
                             <div class="flex mb-2">
                                 <span class="text-gray-600 w-12">From:</span>
                                 {{-- <span class="text-gray-800">May 12, 2024 3:00 PM</span> --}}
                                 <span class="text-gray-800">{{ $carbon->create($concert->reservation_start)->format('F j, Y') }}</span>
                             </div>
                             <div class="flex">
                                 <span class="text-gray-600 w-12">To:</span>
                                 <span class="text-gray-800">{{ $carbon->create($concert->reservation_end)->format('F j, Y') }}</span>
                             </div>
                         </div>
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
                                 <span class="text-gray-800">PHP {{ $concert->general_price }}</span>
                             </div>
                             <div class="flex">
                                 <span class="text-gray-600 w-12">VIP:</span>
                                 <span class="text-gray-800">PHP {{ $concert->vip_price }}</span>
                             </div>
                         </div>
                     </div>
               </div>
          </div>
     </div> 
 </x-app-layout>