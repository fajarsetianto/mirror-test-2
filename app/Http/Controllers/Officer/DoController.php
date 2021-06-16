<?php

namespace App\Http\Controllers\Officer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Pivots\OfficerTarget;
use DataTables;

class DoController extends Controller
{

    protected $viewNamespace = 'pages.officer.inspection.do.';

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(OfficerTarget $officerTarget)
    {
        $officerTarget->load(['target.form','target.officers','target.institutionable','officer']);
        return view($this->viewNamespace.'index', ['item' => $officerTarget]);
    }

    public function data(OfficerTarget $officerTarget){
        $data = $officerTarget->load('target.form')
                    ->target->form
                    ->instruments();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function($row){   
                $link = '<a href="'.route('respondent.form.question.index',[$row->id]).'">'.strtoupper($row->name).'</a>';     
                return $link;
            })
            ->addColumn('question', function($row){   
                return '0/'.$row->questions()->count();
            })
            ->addColumn('status', function($row){   
                return '<span class="badge badge-danger">Belum Lengkap</span>';
            })
            ->addColumn('actions', function($row){   
                $btn = '<div class="list-icons">
                <div class="dropdown">
                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                        <i class="icon-menu9"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="#" class="dropdown-item"><i class="icon-pencil"></i>Edit</a>
                        <a href="#" class="dropdown-item"><i class="icon-download"></i>Unduh</a>
                    </div>
                </div>
            </div>';    
                return $btn;
            })
            ->rawColumns(['name','status','actions'])
            ->make(true);
    }
}
