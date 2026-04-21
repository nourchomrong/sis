<?php

namespace App\Observers;

use App\Models\Subject;

class SubjectObserver
{
    public function created(Subject $subject): void
    {
        cache()->forget('subjects_updated');
        cache()->put('subjects_updated', now(), now()->addSeconds(60));
    }

    public function updated(Subject $subject): void
    {
        cache()->forget('subjects_updated');
        cache()->put('subjects_updated', now(), now()->addSeconds(60));
    }

    public function deleted(Subject $subject): void
    {
        cache()->forget('subjects_updated');
        cache()->put('subjects_updated', now(), now()->addSeconds(60));
    }
}
