<?php

namespace App\Observers;

use App\Models\Teacher;
use Livewire\Livewire;

class TeacherObserver
{
    public function created(Teacher $teacher): void
    {
        Livewire::dispatch('refresh-teachers', ['message' => 'New teacher added']);
    }

    public function updated(Teacher $teacher): void
    {
        Livewire::dispatch('refresh-teachers', ['message' => 'Teacher updated']);
    }

    public function deleted(Teacher $teacher): void
    {
        Livewire::dispatch('refresh-teachers', ['message' => 'Teacher deleted']);
    }
}
