<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Form;
use Illuminate\Auth\Access\Response;

class FormPolicy
{
    public function manage(User $user, Form $form)
    {
        return $form->createdBy->is($user)
            ? Response::allow()
            : Response::deny('You do not own this form.');
    }
}