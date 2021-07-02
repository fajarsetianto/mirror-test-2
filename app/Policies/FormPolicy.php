<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Form;
use App\Models\Instrument;
use Illuminate\Auth\Access\Response;

class FormPolicy
{
    public function manage(User $user, Form $form)
    {
        return $form->createdBy->is($user)
            ? Response::allow()
            : Response::deny('You do not own this form.');
    }

    public function published(User $user, Form $form)
    {
        return !$form->isPublished()
            ? Response::allow()
            : Response::deny('The form has been published.');
    }
}