<?php

namespace App\Http\Controllers\Responden;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DataTables;
use Illuminate\Support\Carbon;

class EndController extends Controller
{

    protected $viewNamespace = 'pages.responden.';
  
    public function stop()
    {
        $user = auth('respondent')->user()->load('target.form');
        if(!$user->isSubmited() && !$user->target->form->isExpired()){
            return \redirect()->route('respondent.dashboard');
        }

        $user = auth('respondent')->user()->load('target.form');
        $completed = $user->isSubmited();
        return view($this->viewNamespace.'end', compact('completed'));
    }
}
