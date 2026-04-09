<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Class Namespace
    |--------------------------------------------------------------------------
    |
    | This value sets the root namespace for Livewire component classes in
    | your application. This value affects component auto-discovery and
    | resolution. You may change it to fit your application's structure.
    |
    */

    'class_namespace' => 'App\\Livewire',

    /*
    |--------------------------------------------------------------------------
    | View Path
    |--------------------------------------------------------------------------
    |
    | This value sets the path for Livewire component views. This affects
    | auto-discovery of views. You may change it to fit your structure.
    |
    */

    'view_path' => resource_path('views/livewire'),

    /*
    |--------------------------------------------------------------------------
    | Temporary File Uploads
    |--------------------------------------------------------------------------
    |
    | Livewire handles file uploads by storing them temporarily on disk before
    | the final form submission. The settings below control the temporary
    | upload route, middleware, and storage disk/directory.
    |
    | middleware: Set to an empty array to allow unauthenticated access to the
    | temporary file upload endpoint. This is required while the application
    | does not have a formal authentication system in place. Replace with
    | ['auth'] (or equivalent) once authentication is implemented.
    |
    */

    'temporary_file_upload' => [
        'disk'        => null,
        'rules'       => null,
        'directory'   => null,
        'middleware'  => [],
        'preview_mimes' => [
            'png', 'gif', 'bmp', 'svg', 'wav', 'mp4',
            'mov', 'avi', 'wmv', 'mp3', 'm4a',
            'jpg', 'jpeg', 'mpga', 'webp', 'wma',
        ],
        'max_upload_time' => 5,
    ],

    /*
    |--------------------------------------------------------------------------
    | Render On Redirect
    |--------------------------------------------------------------------------
    |
    | This value determines if Livewire will render before redirecting. This
    | is useful for showing a loading state before the redirect occurs.
    |
    */

    'render_on_redirect' => false,

];
