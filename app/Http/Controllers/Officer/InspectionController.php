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

    public function detail(Request $request, Target $target){
        if($request->ajax()){
            $data = $target;
            return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function($row){   
                return $row->nonSatuanPendidikan->name;
            })
            ->addColumn('officer_name', function($row){   
                return $row->officerName();
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
                $btn = '<button class="btn btn-success"><i class="mi-visibility"></i> Lihat Detail</button>';     
                return $btn;
            })
            
            ->rawColumns(['status','actions'])
            ->make(true);
        }
        return view($this->viewNamespace.'detail', compact('form'));
    }

    public function data(){
        $data = auth('officer')
                    ->user()
                    ->forms()
                    // ->published()
                    // ->valid()
                    ->latest()
                    ->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function($row){   
                $link = '<a href="'.route('monev.inspection.detail',[$row->id]).'">'.strtoupper($row->name).'</a>';     
                return $link;
            })
            ->addColumn('target', function($row){   
                return '';
            })
            ->addColumn('actions', function($row){   
                $btn = '<div class="list-icons">
                <div class="dropdown">
                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                        <i class="icon-menu9"></i>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="#" class="dropdown-item" onclick="component(`'.route('monev.inspection.detail',[$row->id]).'`)"><i class="icon-pencil"></i> Edit</a>
                        <a href="javascript:void(0)" class="dropdown-item" onclick="destroy(`'.route('monev.form.destroy',[$row->id]).'`)"><i class="icon-trash"></i> Hapus</a>
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
