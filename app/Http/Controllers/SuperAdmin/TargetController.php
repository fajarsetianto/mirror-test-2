<?php

namespace  App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\Target;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Validation\Rule;
class TargetController extends Controller
{
    protected $viewNamespace = "pages.super-admin.monitoring-evaluasi.sasaran-monitoring.";


    public function summary(Form $form){
        $form->load('targets');
        return view($this->viewNamespace.'summary',[
            'form' => $form
        ]);
    }

}
