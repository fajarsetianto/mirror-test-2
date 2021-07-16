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

    public function data(){
        $data = auth()->user()->forms()->published()->valid()->latest();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function($row){   
                $link = '<a href="'.route('admin.monev.inspection.form.index',[$row->id]).'">'.strtoupper($row->name).'</a>';     
                return $link;
            })
            ->addColumn('target', function($row){   
                $link = '<button onclick="component(`'.route('admin.monev.form.target.summary',[$row->id]).'`)" class="edit btn btn-success btn-sm">Lihat Sasaran Monitoring</button>';     
                return $link;
            })
            ->addColumn('actions', function($row){   
                $btn = '<a href="'.route('admin.monev.inspection.form.index',[$row->id]).'" class="btn btn-info">Lihat Detail</a>';     
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
