<?php

namespace  App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Form;
use App\Models\Indicator;
use DataTables;

class IndicatorReportController extends Controller
{
    protected $viewNamespace = "pages.admin.monitoring-evaluasi.indicator-report.";

    public function index(){
        return view($this->viewNamespace.'index');
    }

    public function detail(Form $form){
        $form->load('indicators.targetsWithScore');
        return view($this->viewNamespace.'detail', compact('form'));
    }

    public function indicatorDetail(Form $form, Indicator $indicator){
        return view($this->viewNamespace.'indicator.detail', compact('form','indicator'));
    }

    public function data(){
        $data = auth()->user()
                    ->forms()
                    ->published()
                    ->expired();
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
    public function detailIndicator(Form $form, Indicator $indicator){
        return view($this->viewNamespace.'indicator.index', compact('form','indicator'));
    }
    
    public function detailIndicatorData(Form $form, Indicator $indicator){
        $data = $indicator->targetsWithScore()->with('institutionable','form')->get();

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
                return $row->officerName();
            })
            // ->addColumn('actions', function($row){   
            //     $btn = '<div class="list-icons">
            //     <div class="dropdown">
            //         <a href="#" class="list-icons-item" data-toggle="dropdown">
            //             <i class="icon-menu9"></i>
            //         </a>

            //         <div class="dropdown-menu dropdown-menu-right">
            //             <a href="#" class="dropdown-item"><i class="icon-file-word"></i> Export to .doc</a>
            //         </div>
            //     </div>
            // </div>';     
            //     return $btn;
            // })
            ->addColumn('score', function($row){
                return $row->respondent->score;
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
            ->rawColumns(['name','target','status','category'])
            ->make(true);
    }
}
