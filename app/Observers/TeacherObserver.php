<?php

namespace App\Observers;

use App\Models\Teacher;

class TeacherObserver
{
    public function created(Teacher $teacher): void
    {
        cache()->forget('teachers_updated');
        cache()->put('teachers_updated', now(), now()->addSeconds(60));
    }

    public function updated(Teacher $teacher): void
    {
        cache()->forget('teachers_updated');
        cache()->put('teachers_updated', now(), now()->addSeconds(60));
    }

    public function deleted(Teacher $teacher): void
    {
        cache()->forget('teachers_updated');
        cache()->put('teachers_updated', now(), now()->addSeconds(60));
    }
}
