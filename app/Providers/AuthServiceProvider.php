<?php

namespace App\Providers;

use App\Models\Form;
use App\Models\Institution;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Officer;
use App\Policies\FormPolicy;
use App\Policies\InstitutionPolicy;
use Illuminate\Support\Facades\Gate;
use App\Policies\OfficerPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Officer::class => OfficerPolicy::class,
        Form::class => FormPolicy::class,
        Institution::class => InstitutionPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
