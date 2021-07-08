<?php

namespace App\Http\Controllers\SuperAdmin\Inspection\History;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\Target;
use Illuminate\Http\Request;
use DataTables;

class InspectionHistoryController extends Controller
{
    protected $viewNamespace = "pages.super-admin.monitoring-evaluasi.inspection-history.";

    public function index(){
        return view($this->viewNamespace.'index');
    }

    public function data(){
        $data = Form::published()
                    ->expired()
                    ->latest();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function($row){   
                $link = '<a href="'.route('superadmin.monev.inspection-history.form.index',[$row->id]).'">'.strtoupper($row->name).'</a>';     
                return $link;
            })
            ->addColumn('target', function($row){   
                $link = '<button onclick="component(`'.route('superadmin.monev.form.target.summary',[$row->id]).'`)" class="edit btn btn-success btn-sm">Lihat Sasaran Monitoring</button>';     
                return $link;
            })
            ->addColumn('actions', function($row){   
                $btn = '<div class="list-icons">
                <div class="dropdown">
                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                        <i class="icon-menu9"></i>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="'.route('superadmin.monev.inspection-history.form.index',[$row->id]).'" class="dropdown-item"><i class="icon-eye"></i> Lihat Detail</a>
                        <a href="javascript:void(0)" class="dropdown-item"><i class="icon-download"></i> Unduh</a>
                    </div>
                </div>
            </div>';     
                return $btn;
            })
            ->rawColumns(['name','target','actions'])
            ->make(true);
    }

    
}
