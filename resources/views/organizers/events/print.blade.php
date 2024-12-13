<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
     <head>
          <meta charset="utf-8">
          <meta name="viewport" content="width=device-width, initial-scale=1">

          <title>Concert Ticketing</title>

          <!-- Fonts -->
          <link rel="preconnect" href="https://fonts.bunny.net">
          <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

          <!-- Styles -->
          @vite(['resources/css/app.css', 'resources/js/app.js'])
     </head>
     <body class="antialiased font-sans">
          <div class="min-h-screen">
               <div class="mx-auto max-w-7xl mt-12">
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
                                    <h1 class="col-span-5 text-center">{{ $carbon->create($ticket->bought_at)->format("F j, Y g:i:s A") }}</h1>
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
          </div>
     </body>
</html>