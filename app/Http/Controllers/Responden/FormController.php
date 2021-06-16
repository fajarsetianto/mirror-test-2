<?php

namespace App\Http\Controllers\Responden;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DataTables;
use Illuminate\Support\Carbon;

class FormController extends Controller
{

    protected $viewNamespace = 'pages.responden.';

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth('respondent')->user()->load('target.form');
        return view($this->viewNamespace.'form', ['form' => $user->target->form,'user' => $user]);
    }

    public function publish(){
        $user =  auth('respondent')->user();
        $passed = $user->isAllQuestionsAnswered();
        $failed_note = '';
        if(!$passed){
            $failed_note = "Anda belum menjawab semua pertanyaan. silahhkan cek kembali";
        }
        return view($this->viewNamespace.'publish', compact('passed','failed_note'));
    }

    public function publishing(){
        $user =  auth('respondent')->user();
        $passed = $user->isAllQuestionsAnswered();
        if($passed){
            $user->update(['submited_at'=> Carbon::now()]);
        }
        return redirect()->route('respondent.stop');
    }

    public function data(){
        $data = auth('respondent')->user()->load('target.form.instruments')->target->form->instruments();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function($row){   
                $link = '<a href="'.route('respondent.form.question.index',[$row->id]).'">'.strtoupper($row->name).'</a>';     
                return $link;
            })
            ->addColumn('question', function($row){   
                return auth()->user()->answeredQuestionsCountByInstrument($row).'/'.$row->questions()->count();
            })
            ->addColumn('status', function($row){
                if(auth()->user()->answeredQuestionsCountByInstrument($row) == $row->questions()->count()){
                    return '<span class="badge badge-success">Lengkap</span>';
                }
                return '<span class="badge badge-danger">Belum Lengkap</span>';
                
            })
            ->addColumn('actions', function($row){   
                $btn = '<div class="list-icons">
                <div class="dropdown">
                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                        <i class="icon-menu9"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="#" class="dropdown-item"><i class="icon-pencil"></i>Edit</a>
                        <a href="#" class="dropdown-item"><i class="icon-download"></i>Unduh</a>
                    </div>
                </div>
            </div>';    
                return $btn;
            })
            ->rawColumns(['name','status','actions'])
            ->make(true);
    }
}
