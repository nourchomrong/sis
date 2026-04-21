<?php

namespace App\Observers;

use App\Models\Classes;
use Livewire\Livewire;

class ClassesObserver
{
    public function created(Classes $classes): void
    {
        Livewire::dispatch('refresh-classes', ['message' => 'New class added']);
    }

    public function updated(Classes $classes): void
    {
        Livewire::dispatch('refresh-classes', ['message' => 'Class updated']);
    }

    public function deleted(Classes $classes): void
    {
        Livewire::dispatch('refresh-classes', ['message' => 'Class deleted']);
    }
}
