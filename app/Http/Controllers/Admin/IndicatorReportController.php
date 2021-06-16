<?php

namespace  App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Form;
use DataTables;

class IndicatorReportController extends Controller
{
    protected $viewNamespace = "pages.admin.monitoring-evaluasi.indicator-report.";

    public function index(){
        return view($this->viewNamespace.'index');
    }

    public function detail(Form $form){
        $form->load('indicators');
        return view($this->viewNamespace.'detail', compact('form'));
    }

    public function data(){
        $data = auth()->user()
                    ->forms();
                    // ->published()
                    // ->expired();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function($row){   
                $link = '<a href="'.route('monev.indicator-report.detail',[$row->id]).'">'.strtoupper($row->name).'</a>';     
                return $link;
            })
            ->addColumn('target', function($row){   
                $link = '<a href="#" class="edit btn btn-success btn-sm">Lihat Sasaran Monitoring</a>';     
                return $link;
            })
            ->addColumn('actions', function($row){   
                $btn = '<div class="list-icons">
                <div class="dropdown">
                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                        <i class="icon-menu9"></i>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right">
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
