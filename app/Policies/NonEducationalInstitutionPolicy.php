<?php
namespace App\Policies;

use App\Models\User;
use App\Models\NonEducationalInstitution;
use Illuminate\Auth\Access\Response;

class NonEducationalInstitutionPolicy
{
    public function manage(User $user, NonEducationalInstitution $NonEducationalInstitution)
    {
        return $NonEducationalInstitution->createdBy->is($user)
            ? Response::allow()
            : Response::deny('You do not own this Institution.');
    }
}