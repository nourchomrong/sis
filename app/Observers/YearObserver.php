<?php

namespace App\Observers;

use App\Models\Year;

class YearObserver
{
    public function created(Year $year): void
    {
        cache()->forget('years_updated');
        cache()->put('years_updated', now(), now()->addSeconds(60));
    }

    public function updated(Year $year): void
    {
        cache()->forget('years_updated');
        cache()->put('years_updated', now(), now()->addSeconds(60));
    }

    public function deleted(Year $year): void
    {
        cache()->forget('years_updated');
        cache()->put('years_updated', now(), now()->addSeconds(60));
    }
}
