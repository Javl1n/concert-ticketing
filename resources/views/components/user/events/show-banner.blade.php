@props(['concert'])

@php
     use Illuminate\Support\Carbon;
@endphp

<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
     {{-- <img src="{{ asset('storage/sample.png') }}" alt=""> --}}
     
     <a href="{{ route('user.concert.show', [$concert]) }}" class="">
          <div style='background-image: url("{{ asset($concert->image->url) }}");' class="rounded-3xl flex flex-col justify-between bg-center bg-cover bg-no-repeat bg-white">
               <div class="bg-black/30 px-20 py-10 rounded-3xl">
                    <div class="max-w-xl h-96 flex flex-col justify-center gap-2 text-white">
                         <p class="text-md font-bold">{{ Carbon::create($concert->concert_start)->format('F d, o') }}</p>
                         <h5 class="mb-2 text-7xl font-semibold tracking-tight">
                              {{ $concert->name }}
                         </h5>
                         <p class="mb-3 font-normal">
                              {{ Str::limit($concert->description, 300) }}
                         </p>
                    </div>
               </div>
          </div>
     </a>
 </div>