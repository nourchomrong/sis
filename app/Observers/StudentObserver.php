<?php

namespace App\Observers;

use App\Models\Student;
use Livewire\Livewire;

class StudentObserver
{
    /**
     * Handle the Student "created" event.
     */
    public function created(Student $student): void
    {
        Livewire::dispatch('refresh-students', ['message' => 'New student added']);
    }

    /**
     * Handle the Student "updated" event.
     */
    public function updated(Student $student): void
    {
        Livewire::dispatch('refresh-students', ['message' => 'Student updated']);
    }

    /**
     * Handle the Student "deleted" event.
     */
    public function deleted(Student $student): void
    {
        Livewire::dispatch('refresh-students', ['message' => 'Student deleted']);
    }
}
