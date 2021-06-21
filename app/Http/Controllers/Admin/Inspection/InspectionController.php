<?php

namespace App\Http\Controllers\Admin\Inspection;

use App\Http\Controllers\Controller;
use App\Models\Form;
use Illuminate\Http\Request;
use DataTables;

class InspectionController extends Controller
{
    protected $viewNamespace = "pages.admin.monitoring-evaluasi.inspection.";

    public function index(){
        return view($this->viewNamespace.'index');
    }

    public function detail(Request $request, Form $form){
        if($request->ajax()){
            $data = $form->targets()->latest();
            return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function($row){   
                return $row->institutionable->name;
            })
            ->addColumn('officer_name', function($row){   
                return $row->officerName();
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
            ->addColumn('actions', function($row) use ($form){   
                $btn = '<button class="btn btn-success"><i class="mi-visibility"></i> Lihat Detail</button>';     
                return $btn;
            })
            
            ->rawColumns(['status','actions'])
            ->make(true);
        }
        return view($this->viewNamespace.'detail', compact('form'));
    }


    public function data(){
        $data = auth()->user()->forms()->published()->valid()->latest();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function($row){   
                $link = '<a href="'.route('admin.monev.inspection.detail',[$row->id]).'">'.strtoupper($row->name).'</a>';     
                return $link;
            })
            ->addColumn('target', function($row){   
                $link = '<button onclick="component(`'.route('admin.monev.form.target.summary',[$row->id]).'`)" class="edit btn btn-success btn-sm">Lihat Sasaran Monitoring</button>';     
                return $link;
            })
            ->addColumn('actions', function($row){   
                $btn = '<div class="list-icons">
                <div class="dropdown">
                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                        <i class="icon-menu9"></i>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="#" class="dropdown-item" onclick="component(`'.route('admin.monev.inspection.detail',[$row->id]).'`)"><i class="icon-pencil"></i> Edit</a>
                        <a href="javascript:void(0)" class="dropdown-item" onclick="destroy(`'.route('admin.monev.form.destroy',[$row->id]).'`)"><i class="icon-trash"></i> Hapus</a>
                        <a href="#" class="dropdown-item"><i class="icon-file-word"></i> Export to .doc</a>
                    </div>
                </div>
            </div>';     
                return $btn;
            })
            ->addColumn('status', function($row){   
                $btn = '<span class="badge badge-primary">'.$row->status.'</span>';     
                return $btn;
            })
            ->rawColumns(['name','target','actions','status'])
            ->make(true);
    }

    
}
