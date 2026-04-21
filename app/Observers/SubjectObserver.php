<?php

namespace App\Observers;

use App\Models\Subject;
use Livewire\Livewire;

class SubjectObserver
{
    public function created(Subject $subject): void
    {
        Livewire::dispatch('refresh-subjects', ['message' => 'New subject added']);
    }

    public function updated(Subject $subject): void
    {
        Livewire::dispatch('refresh-subjects', ['message' => 'Subject updated']);
    }

    public function deleted(Subject $subject): void
    {
        Livewire::dispatch('refresh-subjects', ['message' => 'Subject deleted']);
    }
}
