<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\Form;
use Illuminate\Http\Request;
use DataTables;

class InspectionController extends Controller
{
    protected $viewNamespace = "pages.officer.inspection.";

    public function index(){
        return view($this->viewNamespace.'index');
    }

    public function data(){
        $data = auth('officer')
                ->user()
                ->targets()
                ->whereHas('officerLeader',function($q){
                    $q->where('officer_targets.submited_at', '=', null);
                })
                ->whereHas('form', function($item){
                    $item->where(function($item){
                        $item->published()->valid();
                    });
                })
                ->with('form','institutionable')
                ->select(['targets.*','officer_targets.id as pivot_id']);
                    
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function($row){   
                $link = $row->form->name;     
                return $link;
            })
            ->addColumn('target_name', function($row){   
                return $row->institutionable->name;
            })
            ->addColumn('category', function($row){   
                return '<span class="badge badge-success">'.$row->form->category.'</span>';
            })    
            ->addColumn('expired_date', function($row){   
                return $row->form->supervision_end_date->format('d/m/Y');
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
                $btn = '<a href="'.route('officer.monev.inspection.do.index',[$row->pivot_id]).'" class="btn btn-primary btn-sm">
                            <i class="mi-assignment"></i>
                            Isi Form Monitoring
                        </a>';
                return $btn;
            })
            ->rawColumns(['name','target','actions','status','category'])
            ->make(true);
    }

    
}
