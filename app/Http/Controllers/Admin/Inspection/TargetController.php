<?php

namespace App\Http\Controllers\Admin\Inspection;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\Target;
use Illuminate\Http\Request;
use DataTables;

class TargetController extends Controller
{
    protected $viewNamespace = "pages.admin.monitoring-evaluasi.inspection.sasaran-monitoring.";

    public function index(Form $form){
        return view($this->viewNamespace.'index', compact('form'));
    }

    public function detail(Form $form, Target $target){
        $target->load('respondent');
        return view($this->viewNamespace.'detail', compact('form','target'));
    }

    public function data(Form $form){
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
            $btn = '<div class="list-icons">
                <div class="dropdown">
                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                        <i class="icon-menu9"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="'.route('admin.monev.inspection.form.detail',[$form->id,$row->id]).'" class="dropdown-item"><i class="icon-eye"></i> Lihat Detail</a>
                        <a href="javascript:void(0)" class="dropdown-item"><i class="icon-download"></i> Unduh</a>
                        <a href="javascript:void(0)" class="dropdown-item" onclick="destroy(`'.route('admin.monev.form.destroy',[$row->id]).'`)"><i class="icon-trash"></i> Hapus</a>
                    </div>
                </div>
            </div>';     
                return $btn;
        })
        
        ->rawColumns(['status','actions'])
        ->make(true);
    }

    
}