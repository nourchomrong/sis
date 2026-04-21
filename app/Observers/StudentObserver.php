<?php

namespace App\Observers;

use App\Models\Student;

class StudentObserver
{
    /**
     * Handle the Student "created" event.
     */
    public function created(Student $student): void
    {
        cache()->forget('students_updated');
        cache()->put('students_updated', now(), now()->addSeconds(60));
    }

    /**
     * Handle the Student "updated" event.
     */
    public function updated(Student $student): void
    {
        cache()->forget('students_updated');
        cache()->put('students_updated', now(), now()->addSeconds(60));
    }

    /**
     * Handle the Student "deleted" event.
     */
    public function deleted(Student $student): void
    {
        cache()->forget('students_updated');
        cache()->put('students_updated', now(), now()->addSeconds(60));
    }
}
