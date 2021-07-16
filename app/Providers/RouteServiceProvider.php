<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        $this->mapSuperAdminRoutes();

        $this->mapAdminRoutes();
        
        $this->mapRespondenRoutes();

        $this->mapOfficerRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace.'\Api')
            ->group(base_path('routes/api.php'));
    }

    protected function mapSuperAdminRoutes()
    {
        Route::middleware(['web','auth','admin.eligible'])
            ->name('superadmin.')
            ->prefix('superadmin')
            ->namespace($this->namespace.'\SuperAdmin')
            ->group(base_path('routes/superadmin.php'));
    }

    protected function mapAdminRoutes()
    {
        Route::middleware(['web','auth','admin.eligible'])
            ->name('admin.')
            ->prefix('admin')
            ->namespace($this->namespace.'\Admin')
            ->group(base_path('routes/admin.php'));
    }

    protected function mapRespondenRoutes()
    {
        Route::middleware(['web'])
            ->prefix('responden')
            ->name('respondent.')
            ->namespace($this->namespace.'\Responden')
            ->group(base_path('routes/responden.php'));
    }

    protected function mapOfficerRoutes()
    {
        Route::middleware(['web'])
            ->prefix('petugas')
            ->name('officer.')
            ->namespace($this->namespace.'\Officer')
            ->group(base_path('routes/officer.php'));
    }
}
