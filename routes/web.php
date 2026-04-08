<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

//Route::get('/health', fn () => response()->json(['ok' => true]));

Route::get('/', function () {
    return view('pages.home');
});

Route::get('/blog', function () {
    return view('blog.index');
});

Route::post('/contacto', function () {
    request()->validate([
        'nombre' => ['required', 'string', 'max:120'],
        'email' => ['required', 'email', 'max:160'],
        'mensaje' => ['required', 'string', 'max:2000'],
    ]);

    // Basic conversion tracking for contact form submissions.
    Log::info('conversion.contact_form_submitted', [
        'email' => request('email'),
        'source' => 'home_contact_form',
        'path' => request()->path(),
    ]);

    return back()->with('success', 'Gracias por escribirnos. Te contactaremos en menos de 24 horas habiles.');
});
