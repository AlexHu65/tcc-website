<?php

use App\Http\Controllers\BienestarController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CpSensitiveSubmissionController;
use Illuminate\Support\Facades\Route;
use Statamic\Facades\Entry;

// Route::get('/health', fn () => response()->json(['ok' => true]));

Route::get('/', function () {
    $entry = Entry::find('home');
    if (! $entry) {
        abort(404);
    }

    $data = $entry->data()->all();
    $template = $data['template'] ?? 'default';
    $viewName = str_replace('/', '.', $template);

    return view($viewName, $data);
});

Route::get('/blog', [BlogController::class, 'index']);

Route::get('/bienestar', [BienestarController::class, 'show'])->name('bienestar');

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
