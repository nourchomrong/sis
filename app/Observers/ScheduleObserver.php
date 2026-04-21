<?php

namespace App\Observers;

use App\Models\Schedule;
use Livewire\Livewire;

class ScheduleObserver
{
    public function created(Schedule $schedule): void
    {
        Livewire::dispatch('refresh-schedules', ['message' => 'New schedule added']);
    }

    public function updated(Schedule $schedule): void
    {
        Livewire::dispatch('refresh-schedules', ['message' => 'Schedule updated']);
    }

    public function deleted(Schedule $schedule): void
    {
        Livewire::dispatch('refresh-schedules', ['message' => 'Schedule deleted']);
    }
}
