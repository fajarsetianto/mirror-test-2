<?php

namespace App\Providers;

use App\Models\Form;
use App\Models\NonEducationalInstitution;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Officer;
use App\Models\Pivots\OfficerTarget;
use App\Policies\FormPolicy;
use App\Policies\NonEducationalInstitutionPolicy;
use Illuminate\Support\Facades\Gate;
use App\Policies\OfficerPolicy;
use App\Policies\OfficerTargetPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Form::class => FormPolicy::class,
        Officer::class => OfficerPolicy::class,
        OfficerTarget::class => OfficerTargetPolicy::class,
        NonEducationalInstitution::class => NonEducationalInstitutionPolicy::class,
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
