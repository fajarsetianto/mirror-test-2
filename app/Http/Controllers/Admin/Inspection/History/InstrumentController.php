<?php

namespace  App\Http\Controllers\Admin\Inspection\History;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\Instrument;
use App\Models\Target;
use Illuminate\Http\Request;
use DataTables;

class InstrumentController extends Controller
{
    protected $viewNamespace = "pages.admin.monitoring-evaluasi.inspection-history.instrument.";

    public function data(Form $form, Target $target){
        $target->load('respondent');
        $respondent = $target->respondent;
        $data = $form->instruments()->latest();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function($row) use($target){   
                $link = '<a href="'.route('admin.monev.inspection-history.form.instrument.detail',[$row->form_id, $target->id, $row->id]).'">'.strtoupper($row->name).'</a>';     
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
            ->addColumn('actions', function($row) use ($form,$target){   
                $btn = '<a href="'.route('admin.monev.inspection-history.form.instrument.detail',[$row->form_id, $target->id, $row->id]).'" class="edit btn btn-success btn-sm">Lihat Detail</a>';        
                return $btn;
            })
            ->rawColumns(['actions', 'name'])
            ->make(true);
    }

    public function detail(Form $form, Target $target, Instrument $instrument){
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
        return view($this->viewNamespace.'index',compact('form','target','instrument'));
    }
}
