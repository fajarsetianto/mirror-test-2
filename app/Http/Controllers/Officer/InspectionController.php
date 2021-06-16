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
                    ->wherePivotNull('submited_at')
                    ->whereHas('form', function($item){
                        $item->published()->valid();
                    })
                    ->with('form','institutionable');
                    
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
                        return '<span class="badge badge-warning">Belum Dikerjakan</span>';
                        break;
                    case 'petugas MONEV':
                        return '<span class="badge badge-warning">Belum Dikerjakan</span>';
                        break;
                    case 'responden & petugas MONEV':
                        $res =  '<span class="badge badge-warning">Responden : Belum Dikerjakan</span>';
                        $res.= '<br><span class="badge badge-warning">Petugas : Belum Dikerjakan</span>';
                        return $res;
                        break;
                }
            })
            ->addColumn('actions', function($row){   
                $btn = '<a href="'.route('officer.monev.inspection.do.index',[$row->id]).'" class="btn btn-primary btn-sm">
                            <i class="mi-assignment"></i>
                            Isi Form Monitoring
                        </a>';
                return $btn;
            })
            ->rawColumns(['name','target','actions','status','category'])
            ->make(true);
    }

    
}
