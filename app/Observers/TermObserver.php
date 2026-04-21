<?php

namespace App\Observers;

use App\Models\Term;
use Livewire\Livewire;

class TermObserver
{
    public function created(Term $term): void
    {
        Livewire::dispatch('refresh-terms', ['message' => 'New term added']);
    }

    public function updated(Term $term): void
    {
        Livewire::dispatch('refresh-terms', ['message' => 'Term updated']);
    }

    public function deleted(Term $term): void
    {
        Livewire::dispatch('refresh-terms', ['message' => 'Term deleted']);
    }
}
