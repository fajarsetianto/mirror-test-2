<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Institution;
use Illuminate\Auth\Access\Response;

class InstitutionPolicy
{
    public function manage(User $user, Institution $institution)
    {
        return $institution->createdBy->is($user)
            ? Response::allow()
            : Response::deny('You do not own this post.');
    }
}