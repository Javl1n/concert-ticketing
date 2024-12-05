<x-app-layout>
     <x-slot name="header">
     <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          {{ __("Book a Concert") }}
     </h2>
     </x-slot>

     @livewire('pages.user.events.order', ['concert' => $concert], key($concert->id))     
</x-app-layout>