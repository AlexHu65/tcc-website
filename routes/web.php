<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\CpSensitiveSubmissionController;
use Illuminate\Support\Facades\Route;

// Route::get('/health', fn () => response()->json(['ok' => true]));

Route::get('/blog', [BlogController::class, 'index']);

Route::middleware(['web', 'auth'])
    ->prefix(config('statamic.cp.route', 'cp'))
    ->group(function () {
        Route::get('/forms/contacto/sensitive-submissions', [CpSensitiveSubmissionController::class, 'index']);
        Route::get('/forms/contacto/sensitive-submissions/{id}', [CpSensitiveSubmissionController::class, 'show'])
            ->whereNumber('id');

        Route::get('/sensitive-submissions', [CpSensitiveSubmissionController::class, 'index']);
        Route::get('/sensitive-submissions/{id}', [CpSensitiveSubmissionController::class, 'show'])
            ->whereNumber('id');
    });
