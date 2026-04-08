<?php

namespace App\Providers;

use App\Listeners\StoreSensitiveFormSubmission;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Statamic\Events\FormSubmitted;
use Statamic\Facades\CP\Nav;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(FormSubmitted::class, StoreSensitiveFormSubmission::class);

        Nav::extend(function ($nav) {
            $nav->tools('Datos sensibles')
                ->icon('forms')
                ->url('forms/contacto/sensitive-submissions');
        });
    }
}
