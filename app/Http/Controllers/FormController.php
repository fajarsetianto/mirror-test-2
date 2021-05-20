<?php

namespace App\Http\Controllers;

use App\Models\Form;
use Illuminate\Http\Request;
use DataTables;

class FormController extends Controller
{
    protected $viewNamespace = "pages.admin.monitoring-evaluasi.form.";

    public function index(){
        return view($this->viewNamespace.'index');
    }

    public function create(){
        return view($this->viewNamespace.'form', [
            'url' => route('monev.form.store'),
            'action' => 'POST'
        ]);
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string'
        ]);
        
        $data = $request->only('name','description');
        $data['created_by'] = auth()->user()->id;
        $newForm = Form::create($data);

        return response()->json($newForm, 200);
    }

    public function data(){
        $data = Form::latest()->get();
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function($row){   
                $link = '<a href="'.route('monev.form.instrument.index',[$row->id]).'">'.$row->name.'</a>';     
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
                        <a href="#" class="dropdown-item"><i class="icon-file-pdf"></i> Export to .pdf</a>
                        <a href="#" class="dropdown-item"><i class="icon-file-excel"></i> Export to .csv</a>
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
