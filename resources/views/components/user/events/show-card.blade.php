@props(['concert'])

@php
     use Illuminate\Support\Carbon;
@endphp

<a 
     href="{{ route('user.concert.show', [$concert]) }}" 
     class="items-center justify-center bg-white shadow rounded-xl md:flex-row md:max-w-xl"
>
     <img 
          class="object-cover rounded-t-xl"
          src="{{ asset($concert->image->url) }}" 
          alt=""
     >
     <div class="flex flex-col justify-between py-4 px-4 leading-normal">
          <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">{{ $concert->name }}</h5>
          <p class="text-xs text-gray-500 font-bold mb-2">{{ Carbon::create($concert->concert_start)->format('F d, o') }}</p>
          <p class="mb-3 font-normal text-gray-700">{{ Str::limit($concert->description, 100) }}</p>
     </div>
</a>