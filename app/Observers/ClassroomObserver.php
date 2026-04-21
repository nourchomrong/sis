<?php

namespace App\Observers;

use App\Models\Classroom;
use Livewire\Livewire;

class ClassroomObserver
{
    public function created(Classroom $classroom): void
    {
        Livewire::dispatch('refresh-classrooms', ['message' => 'New classroom added']);
    }

    public function updated(Classroom $classroom): void
    {
        Livewire::dispatch('refresh-classrooms', ['message' => 'Classroom updated']);
    }

    public function deleted(Classroom $classroom): void
    {
        Livewire::dispatch('refresh-classrooms', ['message' => 'Classroom deleted']);
    }
}
