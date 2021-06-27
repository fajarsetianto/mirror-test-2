<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\Pivots\OfficerTarget;
use Illuminate\Http\Request;
use DataTables;

class InspectionHistoryController extends Controller
{
    protected $viewNamespace = "pages.officer.inspection.history.";

    public function index(){
        return view($this->viewNamespace.'index');
    }

    public function detail(Request $request, OfficerTarget $officerTarget){
        $officerTarget->load(['target.form','target.officers','target.institutionable','officer']);
        if($request->ajax()){
            $data = $officerTarget->load('target.form')
                    ->target->form
                    ->instruments();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function($row){   
                $link = '<a href="'.route('respondent.form.question.index',[$row->id]).'">'.strtoupper($row->name).'</a>';     
                return $link;
            })
            ->addColumn('question', function($row){   
                return '0/'.$row->questions()->count();
            })
            ->addColumn('status', function($row){   
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
        return view($this->viewNamespace.'detail', ['item' => $officerTarget]);
    }

    public function data(){
        $data = auth('officer')
                    ->user()
                    ->targets()
                    ->wherePivotNotNull('submited_at')
                    ->whereHas('form', function($item){
                        $item->where(function($item){
                            $item->published();
                        })->orWhere(function($item){
                            $item->published()->expired();
                        });
                    })
                    ->with('form','institutionable')
                    ->select(['targets.*','officer_targets.id as pivot_id']);
                    
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function($row){   
                $link = '<a href="'.route('admin.monev.inspection.form.index',[$row->id]).'">'.strtoupper($row->form->name).'</a>';     
                return $link;
            })
            ->addColumn('target_name', function($row){   
                return $row->institutionable->name;
            })
            ->addColumn('category', function($row){   
                return '<span class="badge badge-success">'.$row->form->category.'</span>';
            })            
            ->addColumn('status', function($row){   
                switch($row->type){
                    case 'responden':
                        if($row->respondent->isSubmited()){
                            return '<span class="badge badge-success">Responden : Sudah Dikerjakan</span>';
                        }else{
                            return '<span class="badge badge-warning">Responden : Belum Dikerjakan</span>';
                        }
                        break;
                    case 'petugas MONEV':
                        return '<span class="badge badge-warning">Belum Dikerjakan</span>';
                        break;
                    case 'responden & petugas MONEV':
                        if($row->respondent->isSubmited()){
                            $res = '<span class="badge badge-success">Responden : Sudah Dikerjakan</span>';
                        }else{
                            $res = '<span class="badge badge-warning">Responden : Belum Dikerjakan</span>';
                        }
                        $res.= '</br>';
                        if($row->isSubmitedByOfficer()){
                            $res .= '<span class="badge badge-success">Petugas : Sudah Dikerjakan</span>';
                        }else{
                            $res .= '<span class="badge badge-warning">Petugas : Belum Dikerjakan</span>';
                        }
                        return $res;
                        break;
                }
            })
            ->addColumn('actions', function($row){   
                $btn = '<a href="'.route('officer.monev.inspection-history.detail.index',[$row->pivot_id]).'" class="btn btn-primary btn-sm">
                            <i class="mi-visibility"></i>
                            Lihat Detail
                        </a>';     
                return $btn;
            })
            ->addColumn('expired_date', function($row){   
                return $row->form->supervision_end_date->format('d/m/Y');
            })
            ->rawColumns(['name','target','category','actions','status'])
            ->make(true);
    }

    
}
