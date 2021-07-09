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
                $link = strtoupper($row->name);     
                return $link;
            })
            ->addColumn('question', function($row) use ($officerTarget){   
                return $row->questions()->byTargetId($officerTarget->target_id)->count().'/'.$row->questions()->count();
            })
            ->addColumn('status', function($row) use ($officerTarget){   
                if($row->questions()->byTargetId($officerTarget->target_id)->count() == $row->questions()->count()){
                    return '<span class="badge badge-success">Lengkap</span>';
                } else {
                    return '<span class="badge badge-danger">Belum Lengkap</span>';
                }
            })
            ->addColumn('actions', function($row) use ($officerTarget){   
                $btn = '<a href="'.route('officer.monev.inspection-history.detail.instrument.show',[$officerTarget->id,$row->id]).'" class="btn btn-primary btn-sm">
                            <i class="mi-visibility"></i>
                            Lihat Detail
                        </a>';     
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
                    ->where(function($q){
                        $q->whereHas('officerLeader',function($q){
                            $q->where('officer_targets.submited_at', '!=', null);
                        })->orWhereHas('form', function($q){
                            $q->where(function($item){
                                $item->published()->expired();
                            });
                        });
                    })
                    ->whereHas('form', function($item){
                        $item->where(function($item){
                            $item->published();
                        });
                    })
                    ->with('form','institutionable')
                    ->select(['targets.*','officer_targets.id as pivot_id']);
                    
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function($row){   
                $link = strtoupper($row->form->name);     
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
                            return '<span class="badge badge-success">Sudah Dikerjakan</span>';
                        }else{
                            return '<span class="badge badge-warning">Belum Dikerjakan</span>';
                        }
                        break;
                    case 'petugas MONEV':
                        if($row->isSubmitedByOfficer()){
                            $res = '<span class="badge badge-success">Sudah Dikerjakan</span>';
                        }else{
                            $res = '<span class="badge badge-warning">Belum Dikerjakan</span>';
                        }
                        return $res;
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
