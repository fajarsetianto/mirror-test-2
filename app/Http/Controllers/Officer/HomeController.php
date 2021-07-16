<?php

namespace App\Http\Controllers\Officer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DataTables;

class HomeController extends Controller
{

    protected $viewNamespace = 'pages.officer.';

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth('officer')->user();
        $query = auth('officer')
                ->user()
                ->targets()
                ->whereHas('form', function($item){
                    $item->published();
                });
        $withRespondentCount = (clone $query)->whereType('responden & petugas MONEV')->count();
        $officerCount = (clone $query)->whereType('petugas MONEV')->count();
        return view($this->viewNamespace.'dashboard', compact('user','withRespondentCount','officerCount'));
    }

    public function respondentData(){
        $data = auth('officer')
                ->user()
                ->targets()
                ->whereType('responden & petugas MONEV')
                ->whereHas('form', function($item){
                    $item->published();
                })
                ->with(['form','institutionable','respondent'])
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
                        return '<span class="badge badge-success">Responden : Sudah Dikerjakan</span>';
                    }else{
                        return '<span class="badge badge-warning">Responden : Belum Dikerjakan</span>';
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
            if($row->isSubmitedByOfficer() || $row->form->isExpired()){
                $btn = '<a href="'.route('officer.monev.inspection-history.detail.index',[$row->pivot_id]).'" class="btn btn-success btn-sm">
                    <i class="mi-visibility"></i>
                    Lihat Detail
                </a>';
                
            }else{
                $btn = '<a href="'.route('officer.monev.inspection.do.index',[$row->pivot_id]).'" class="btn btn-primary btn-sm">
                        <i class="mi-assignment"></i>
                        Isi Form Monitoring
                    </a>';
            }
            return $btn;
        })
        ->rawColumns(['name','target','actions','status','category'])
        ->make(true);
    }

    public function officerData(){
    
        $data = auth('officer')
                ->user()
                ->targets()
                ->whereIn('type',['petugas MONEV'])
                ->whereHas('form', function($item){
                    $item->published();
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
                            return '<span class="badge badge-success">Responden : Sudah Dikerjakan</span>';
                        }else{
                            return '<span class="badge badge-warning">Responden : Belum Dikerjakan</span>';
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
                if($row->isSubmitedByOfficer() || $row->form->isExpired()){
                    $btn = '<a href="'.route('officer.monev.inspection-history.detail.index',[$row->pivot_id]).'" class="btn btn-success btn-sm">
                        <i class="mi-visibility"></i>
                        Lihat Detail
                    </a>';
                    
                }else{
                    $btn = '<a href="'.route('officer.monev.inspection.do.index',[$row->pivot_id]).'" class="btn btn-primary btn-sm">
                            <i class="mi-assignment"></i>
                            Isi Form Monitoring
                        </a>';
                }
                return $btn;
            })
            ->rawColumns(['name','target','actions','status','category'])
            ->make(true);
    }
}
