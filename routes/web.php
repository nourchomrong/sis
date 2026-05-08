<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Login;


Route::get('/', [Login::class, 'index'])->name('login');

Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', fn () => view('admin.dashboard'))
        ->name('admin.dashboard');

    Route::get('/students', fn () => view('admin.student.studentview'))
        ->name('admin.students');

    Route::get('/teachers', fn () => view('admin.teacher.teacherview'))
        ->name('admin.teachers');

    Route::get('/classrooms', fn () => view('admin.classroom.classroomview'))
        ->name('admin.classrooms');

    Route::get('/classes', fn () => view('admin.classes.classesview'))
        ->name('admin.classes');

    Route::get('/academicyears', fn () => view('admin.academicsettings.academicyearview'))
        ->name('admin.academicyears');

    Route::get('/schedules', fn () => view('admin.schedule.scheduleview'))
        ->name('admin.schedules');

    Route::get('/subjects', fn () => view('admin.subject.subjectview'))
        ->name('admin.subjects');

    Route::get('/terms', fn () => view('admin.term.termview'))
        ->name('admin.terms');
});

Route::prefix('student')->middleware(['auth', 'role:student'])->group(function () {

    Route::get('/welcome', fn () => view('pages.student.main'))
        ->name('student.main');
});

Route::get('/logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');

