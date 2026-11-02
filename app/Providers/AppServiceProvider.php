<?php

namespace App\Providers;

use App\Models\Domaine;
use App\Models\Infoproneur;
use App\Models\Projet;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        Carbon::setLocale(config('app.locale'));
        Paginator::defaultView('pagination.default');

        // Compteurs affichés dans le menu latéral
        View::composer('layouts.app', function ($view) {
            $view->with('navCounts', [
                'infoproneurs' => Infoproneur::count(),
                'projets' => Projet::count(),
                'domaines' => Domaine::count(),
            ]);
        });
    }
}
