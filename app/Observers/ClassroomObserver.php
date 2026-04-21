<?php

namespace App\Observers;

use App\Models\Classroom;

class ClassroomObserver
{
    public function created(Classroom $classroom): void
    {
        cache()->forget('classrooms_updated');
        cache()->put('classrooms_updated', now(), now()->addSeconds(60));
    }

    public function updated(Classroom $classroom): void
    {
        cache()->forget('classrooms_updated');
        cache()->put('classrooms_updated', now(), now()->addSeconds(60));
    }

    public function deleted(Classroom $classroom): void
    {
        cache()->forget('classrooms_updated');
        cache()->put('classrooms_updated', now(), now()->addSeconds(60));
    }
}
