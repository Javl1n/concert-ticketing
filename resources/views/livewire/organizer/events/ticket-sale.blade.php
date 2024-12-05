<?php

use function Livewire\Volt\{state};

state([
    'ticket'
]);

?>

<div class="grid grid-cols-12 py-1">
    <h1 class="col-span-1 text-center">{{ $ticket->row + 1 }}</h1>
    <h1 class="col-span-2 text-center">{{ $ticket->column + 1 }}</h1>
    <h1 class="col-span-3">{{ $ticket->vip ? "VIP" : "General Admission" }}</h1>
    <h1 class="col-span-3">{{ $ticket->user ? $ticket->user->name : "Not yet sold" }}</h1>
    <h1 class="col-span-3">Receipt Detail</h1>
</div>
