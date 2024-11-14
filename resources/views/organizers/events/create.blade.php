<x-app-layout>
     <x-slot name="header">
         <h2 class="font-semibold text-xl text-gray-800 leading-tight">
             {{ __('Book A Concert') }}
         </h2>
     </x-slot>
 
     <div class="py-8">
          <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
               <livewire:organizer.events.create :lastDay="$lastDay" :firstDay="$firstDay" />
          </div>
     </div> 
 </x-app-layout>