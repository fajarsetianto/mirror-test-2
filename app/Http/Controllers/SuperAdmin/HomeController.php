<?php

namespace App\Http\Controllers\SuperAdmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Officer;
use App\Models\Form;
use App\Models\NonEducationalInstitution;
use App\Models\EducationalInstitution;

class HomeController extends Controller
{

    protected $viewNamespace = 'pages.super-admin.';

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {   
        $officerCount = Officer::count();
        $formCount = Form::count();
        $educationalCount = EducationalInstitution::count();
        $nonEducationalCount = NonEducationalInstitution::count();
        return view($this->viewNamespace.'dashboard',compact('officerCount','formCount','educationalCount','nonEducationalCount'));
    }
}
