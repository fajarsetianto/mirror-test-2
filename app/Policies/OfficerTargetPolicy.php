<?php
namespace App\Policies;

use App\Models\Form;
use App\Models\Officer;
use App\Models\Pivots\OfficerTarget;
use Illuminate\Auth\Access\Response;

class OfficerTargetPolicy
{
    protected $form,$officer;

    public function manage(Officer $user, OfficerTarget $officerTarget)
    {
        $this->_load($officerTarget);
        return $this->officer->is($user)
            ? Response::allow()
            : Response::deny('You do not belongs to this monitoring.');
    }

    public function do(Officer $user, OfficerTarget $officerTarget){
        $this->_load($officerTarget);
        return !$officerTarget->isSubmited() 
                && $this->form->isPublished() 
                && !$this->form->isExpired()
            ? Response::allow()
            : Response::deny('You can not fill this monitoring');
    }

    public function viewHistory(Officer $user, OfficerTarget $officerTarget){
        $this->_load($officerTarget);
        return $this->form->isPublished() && ($officerTarget->isSubmited() || $this->form->isExpired())
            ? Response::allow()
            : Response::deny('You can not view history of this monitoring');
    }

    public function leader(Officer $user, OfficerTarget $officerTarget){
        $this->_load($officerTarget);
        return $officerTarget->is_leader == 1
            ? Response::allow()
            : Response::deny("You're not leader!");
    }

    protected function _load(OfficerTarget $officerTarget)
    {
        $officerTarget->load('target.form','officer');
        $this->form = $officerTarget->target->form;
        $this->officer = $officerTarget->officer;
    }


}