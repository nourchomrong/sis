<?php

namespace App\Observers;

use App\Models\Year;
use Livewire\Livewire;

class YearObserver
{
    public function created(Year $year): void
    {
        Livewire::dispatch('refresh-academic-years', ['message' => 'New academic year added']);
    }

    public function updated(Year $year): void
    {
        Livewire::dispatch('refresh-academic-years', ['message' => 'Academic year updated']);
    }

    public function deleted(Year $year): void
    {
        Livewire::dispatch('refresh-academic-years', ['message' => 'Academic year deleted']);
    }
}
