<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Officer;
use Illuminate\Auth\Access\Response;

class OfficerPolicy
{
    public function manage(User $user, Officer $officer)
    {
        return $officer->createdBy->is($user)
            ? Response::allow()
            : Response::deny('You do not own this officer.');
    }
}