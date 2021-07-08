<?php

namespace  App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Form;
use App\Models\Indicator;
use DataTables;

class IndicatorReportController extends Controller
{
    protected $viewNamespace = "pages.super-admin.monitoring-evaluasi.indicator-report.";

    public function index(){
        return view($this->viewNamespace.'index');
    }

    public function detail(Form $form){
        $form->load('indicators');
        return view($this->viewNamespace.'detail', compact('form'));
    }

    public function indicatorDetail(Form $form, Indicator $indicator){
        return view($this->viewNamespace.'indicator.detail', compact('form','indicator'));
    }

    public function data(){
        $data = Form::published();
                    // ->expired();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function($row){   
                $link = '<a href="'.route('superadmin.monev.indicator-report.detail',[$row->id]).'">'.strtoupper($row->name).'</a>';     
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
    public function detailIndicator(Form $form, Indicator $indicator){
        return view($this->viewNamespace.'indicator.index', compact('form','indicator'));
    }
    
    public function detailIndicatorData(Form $form, Indicator $indicator){
        $data = $indicator->targetsIn()->with('institutionable','form')->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function($row){   
                $link = strtoupper($row->form->name);
                return $link;
            })
            ->addColumn('target', function($row){   
                return $row->institutionable->name;
            })
            ->addColumn('category', function($row){   
                $btn = '<span class="badge badge-success">'.$row->form->category.'</span>';
                return $btn;
            })
            ->addColumn('officer_name', function($row){   
                return view('layouts.parts.officers',['officers' => $row->officers]);
            })
            ->addColumn('score', function($row){
                return $row->respondent_score + $row->officer_score;
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
            ->rawColumns(['name','target','status','category','officer_name'])
            ->make(true);
    }
}
