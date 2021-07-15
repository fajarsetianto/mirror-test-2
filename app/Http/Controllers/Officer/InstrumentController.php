<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\Instrument;
use App\Models\Pivots\OfficerTarget;
use Illuminate\Http\Request;
use DataTables;

class InstrumentController extends Controller
{
    protected $viewNamespace = "pages.officer.inspection.history.";

    public function show(Request $request, OfficerTarget $officerTarget, Instrument $instrument){
        // $officerTarget->load(['target.form','target.officers','target.institutionable','officer']);
        $target = $officerTarget->target;
        $form = $target->form;
        $instrument->load(['questions' => function($q) use ($target){
            $q->when($target->type == 'responden' || $target->type == 'responden & petugas MONEV', function($q) use ($target){
                $q->with(['userAnswers' => function($q) use ($target){
                    $q->byRespondent($target->respondent);
                }]);
            })->when($target->type == 'petugas MONEV' || $target->type == 'responden & petugas MONEV', function($q) use ($target){
                $q->with(['officerAnswer' => function($q) use ($target){
                    $q->whereTargetId($target->id);
                }]);
            });
        },'questions.offeredAnswer']);
        return view($this->viewNamespace.'instrument',compact('form','target','instrument'));
        // return view($this->viewNamespace.'detail', ['item' => $officerTarget]);
    }
    
}
