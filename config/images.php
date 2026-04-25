<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Image and Logo Storage Configuration
    |--------------------------------------------------------------------------
    |
    | Configure disks, paths, and filenames for different types of images.
    | These can be overridden by environment variables.
    |
    */

    'logo' => [
        'disk' => env('LOGO_DISK', 'public'),
        'path' => env('LOGO_PATH', 'assets/images'),
        'filename' => env('LOGO_FILENAME', 'logo.png'),
    ],

    'image' => [
        'disk' => env('IMAGE_DISK', 'public'),
        'path' => env('IMAGE_PATH', 'assets/images'),
    ],

    'student' => [
        'disk' => env('STUDENT_DISK', 'public'),
        'path' => env('STUDENT_PATH', 'students'),
    ],

    'teacher' => [
        'disk' => env('TEACHER_DISK', 'public'),
        'path' => env('TEACHER_PATH', 'teachers'),
    ],

    'photo' => [
        'disk' => env('PHOTO_DISK', 'public'),
        'path' => env('PHOTO_PATH', 'photos'),
    ],

];