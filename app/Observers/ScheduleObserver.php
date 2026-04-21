<?php

namespace App\Observers;

use App\Models\Schedule;

class ScheduleObserver
{
    public function created(Schedule $schedule): void
    {
        cache()->forget('schedules_updated');
        cache()->put('schedules_updated', now(), now()->addSeconds(60));
    }

    public function updated(Schedule $schedule): void
    {
        cache()->forget('schedules_updated');
        cache()->put('schedules_updated', now(), now()->addSeconds(60));
    }

    public function deleted(Schedule $schedule): void
    {
        cache()->forget('schedules_updated');
        cache()->put('schedules_updated', now(), now()->addSeconds(60));
    }
}
