<?php

namespace App\Providers;

use App\Listeners\StoreSensitiveFormSubmission;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Statamic\Events\FormSubmitted;
use Statamic\Facades\CP\Nav;
use Statamic\Facades\Entry;
use Statamic\Facades\Nav as NavFacade;
use Statamic\Facades\Site;

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

        View::composer('layout', function ($view) {
            $pages = collect();
            if (Schema::hasTable('navigations')) {
                $nav = NavFacade::findByHandle('menu_principal');
                if ($nav) {
                    $tree = $nav->in(Site::current());
                    if ($tree) {
                        $pages = $tree->pages()->all();
                    }
                }
            }
            $view->with('mainNavPages', $pages);

            $defaultLogoFile = 'a_minimalist_elegant_logo_brand_mark_on_a_pale_be.png';
            $ilse_logo_asset = asset('images/ilse/'.$defaultLogoFile);

            $home = Entry::find('home');
            if ($home) {
                foreach ($home->data()->all() as $key => $value) {
                    if (str_starts_with((string) $key, 'ilse_')) {
                        $view->with($key, $value);
                    }
                }
                $logoFile = $home->get('ilse_logo');
                if (is_string($logoFile) && $logoFile !== '') {
                    $ilse_logo_asset = asset('images/ilse/'.$logoFile);
                }
            }

            $view->with('ilse_logo_asset', $ilse_logo_asset);
        });

        Nav::extend(function ($nav) {
            $nav->tools('Datos sensibles')
                ->icon('forms')
                ->url('forms/contacto/sensitive-submissions');
        });
    }
}
