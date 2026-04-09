<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Login;

// Allow unauthenticated Livewire file uploads
Route::post('/livewire-{id}/upload-file', function () {
    // This route is handled by Livewire internally
})->withoutMiddleware(['auth', 'verified']);

Route::get('/', [Login::class, 'index'])->name('login');

Route::get('admin/dashboard', function () {
    return view('admin.dashboard');
})->name('dashboard');

Route::get('admin/students', function () {
    return view('admin.student.studentview');
})->name('students');

Route::get('admin/teachers', function () {
    return view('admin.teacher.teacherview');
})->name('teachers');

Route::get('admin/classrooms', function () {
    return view('admin.classroom.classroomview');
})->name('classroom');

Route::get('admin/classes', function () {
    return view('admin.classes.classesview');
})->name('classes');

Route::get('admin/academicyears', function () {
    return view('admin.academicsettings.academicyearview');
})->name('academicyears');

Route::get('admin/schedules', function () {
    return view('admin.schedule.scheduleview');
})->name('schedules');

Route::get('admin/subjects', function () {
    return view('admin.subject.subjectview');
})->name('subjects');

Route::get('admin/terms', function () {
    return view('admin.term.termview');
})->name('terms');