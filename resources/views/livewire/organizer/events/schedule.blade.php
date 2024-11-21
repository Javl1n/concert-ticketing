<?php

use function Livewire\Volt\{state};
use Illuminate\Support\Carbon;

state([
    'now' => Carbon::now(),
    'days' => collect([]),
    'concerts' => App\Models\Concert::all(),
]);

$nextMonth = function () {
    $this->days = collect([]);
    $this->now->setDay(1);

    $this->now->setMonth($this->now->month + 1);
};

$prevMonth = function () {
    if ($this->now->month == Carbon::now()->month) {
        return;
    }
    $this->days = collect([]);
    $this->now->setDay(1);
    $this->now->setMonth($this->now->month - 1);
    if ($this->now->month == Carbon::now()->month) {
        $this->now = Carbon::now();
    }
};

$selectDay = function (int $day) {
    if($day < $this->now->day) {
        return;
    }

    if($this->days->search($day) === false) {
        $this->days = $this->days->sort();
        if (!($this->days->first() - 1 === $day || $this->days->last() + 1 === $day)) {
            $this->days = collect([]);
        }
        return $this->days->push($day);
    } else {
        if ($this->days->first() === $day) {
            $this->days = $this->days->reject(fn (int $value, int $key) => $value == $day);
        } else {
            $this->days = $this->days->reject(fn (int $value, int $key) => $value >= $day);
        }
    }
};

$nextPage = function () {
    if ($this->days->isEmpty()) {
        return;
    }
    $this->days = $this->days->sort();
    $firstDay = Carbon::create($this->now->year, $this->now->month, $this->days->first());
    $lastDay = Carbon::create($this->now->year, $this->now->month, $this->days->last(), 23, 59, 59);
    return redirect()->route('organizer.concerts.create', [
        'firstDay' => $firstDay->toString(),
        'lastDay' => $lastDay->toString(),
    ]);
};

?>

<div class="">
    <div class="border mt-4">
        <div class="flex gap-1 mx-2 my-2">
            <button wire:click='prevMonth' class="my-auto"><x-icons icon="chevron-left" class="h-6 text-gray-500"/></button>
            <button wire:click='nextMonth' class="my-auto"><x-icons icon="chevron-right" class="h-6 text-gray-500"/></button>
            <div class="text-lg p-2">{{ $this->now->copy()->format('F Y') }}</div>
        </div>
        <div class="grid grid-cols-7 text-center border-y">
            <div class="">Sun</div>
            <div class="">Mon</div>
            <div class="">Tue</div>
            <div class="">Wed</div>
            <div class="">Thu</div>
            <div class="">Fri</div>
            <div class="">Sat</div>
        </div>
        <div class="grid grid-cols-7">
            @for($blankStart = 1; $blankStart <= $this->now->copy()->day(1)->dayOfWeek; $blankStart++)
                <div class="h-32 border pt-2 ps-2">
                </div>
            @endfor
            @for ($item = 1; $item <= $this->now->copy()->daysInMonth; $item++)
                @if($concert = $this->concerts->where('reservation_start', '<=', $this->now->copy()->day($item)->format('Y-m-d'))->where('reservation_end', '>=', $this->now->copy()->day($item)->format('Y-m-d'))->first())
                    
                    <div class="text-sm h-32 border pt-2 ps-2 transition text-white font-bold {{ $concert->organizer->id === auth()->user()->id ? 'bg-green-500 ' : 'bg-red-500' }}">
                        {{ $this->now->copy()->day($item)->format('j') }}
                    </div>
                @else
                    <div wire:click='selectDay({{ $item }})' class="text-sm h-32 border pt-2 ps-2 transition cursor-pointer {{ $this->days->search($item) !== false ? 'bg-purple-500 text-white font-bold border-purple-500' : 'hover:bg-purple-100' }}">
                        {{ $this->now->copy()->day($item)->format('j') }}
                    </div>
                @endif
                
            @endfor
            @for($blankEnd = 0; $blankEnd < 6 - $this->now->copy()->day($this->now->daysInMonth)->dayOfWeekIso; $blankEnd++)
                <div class="h-32 border pt-2 ps-2">
                </div>
            @endfor
        </div>
    </div>
    <div class="flex justify-end gap-2 mt-4">
        <a href="{{ route('organizer.concerts.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-transparent rounded-md font-semibold text-xs text-slate-900 uppercase tracking-widest transition ease-in-out duration-150">Cancel</a>
        <x-primary-button class="{{ $this->days->isEmpty() ? 'bg-slate-500' : '' }}" wire:click='nextPage'>Next</x-primary-button>
    </div>
</div>
