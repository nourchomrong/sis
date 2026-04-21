<?php

namespace App\Observers;

use App\Models\Term;

class TermObserver
{
    public function created(Term $term): void
    {
        cache()->forget('terms_updated');
        cache()->put('terms_updated', now(), now()->addSeconds(60));
    }

    public function updated(Term $term): void
    {
        cache()->forget('terms_updated');
        cache()->put('terms_updated', now(), now()->addSeconds(60));
    }

    public function deleted(Term $term): void
    {
        cache()->forget('terms_updated');
        cache()->put('terms_updated', now(), now()->addSeconds(60));
    }
}
