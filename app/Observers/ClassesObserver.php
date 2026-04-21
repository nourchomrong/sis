<?php

namespace App\Observers;

use App\Models\Classes;

class ClassesObserver
{
    public function created(Classes $classes): void
    {
        cache()->forget('classes_updated');
        cache()->put('classes_updated', now(), now()->addSeconds(60));
    }

    public function updated(Classes $classes): void
    {
        cache()->forget('classes_updated');
        cache()->put('classes_updated', now(), now()->addSeconds(60));
    }

    public function deleted(Classes $classes): void
    {
        cache()->forget('classes_updated');
        cache()->put('classes_updated', now(), now()->addSeconds(60));
    }
}
