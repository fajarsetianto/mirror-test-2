<?php

namespace  App\Http\Controllers\Admin\Inspection;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\Instrument;
use App\Models\Target;
use Illuminate\Http\Request;
use DataTables;

class InstrumentController extends Controller
{
    public function data(Form $form, Target $target){
        $target->load('respondent');
        $respondent = $target->respondent;
        $data = $form->instruments()->latest();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function($row){   
                $link = '<a href="'.route('admin.monev.form.instrument.question.index',[$row->form_id, $row->id]).'">'.strtoupper($row->name).'</a>';     
                return $link;
            })
            ->addColumn('questions_count', function($row){   
                return $row->questions()->count();
            })
            ->addColumn('max_score', function($row){   
                return $row->maxScore();
            })
            ->addColumn('score', function($row) use ($target, $form){   
                return $target->score($row);
            })
            ->addColumn('actions', function($row) use ($form){   
                $btn = '<button class="edit btn btn-success btn-sm">Lihat Detail</button>';        
                return $btn;
            })
            ->rawColumns(['actions', 'name'])
            ->make(true);
    }

    public function download(Form $form, Target $target){
        $data = $target->form()->with(['instruments.questions' => function($q) use ($target){
            $q->when($target->type == 'responden' || $target->type == 'responden & petugas MONEV', function($q) use ($target){
                $q->with(['userAnswers' => function($q) use ($target){
                    $q->whereRespondentId($target->respondent->id);
                }]);
            })->when($target->type == 'petugas' || $target->type == 'responden & petugas MONEV', function($q) use ($target){
                $q->with(['officerAnswer' => function($q) use ($target){
                    $q->whereTargetId($target->id);
                }]);
            });
        },'instruments.questions.offeredAnswer'])
        ->get(); 
        return view('layouts.form.respondent',compact('data','target'));
    }
}
