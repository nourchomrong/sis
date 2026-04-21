<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use App\Models\Student;
use App\Models\Term;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Classroom;
use App\Models\Classes;
use App\Models\Schedule;
use App\Models\Year;
use App\Observers\StudentObserver;
use App\Observers\TermObserver;
use App\Observers\TeacherObserver;
use App\Observers\SubjectObserver;
use App\Observers\ClassroomObserver;
use App\Observers\ClassesObserver;
use App\Observers\ScheduleObserver;
use App\Observers\YearObserver;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        // Register model observers for real-time table refresh
        Student::observe(StudentObserver::class);
        Term::observe(TermObserver::class);
        Teacher::observe(TeacherObserver::class);
        Subject::observe(SubjectObserver::class);
        Classroom::observe(ClassroomObserver::class);
        Classes::observe(ClassesObserver::class);
        Schedule::observe(ScheduleObserver::class);
        Year::observe(YearObserver::class);
    }
}