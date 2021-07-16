<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Officer;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    public function publish(User $user)
    {
        return $officer->createdBy->is($user)
            ? Response::allow()
            : Response::deny('You do not own this officer.');
    }
}