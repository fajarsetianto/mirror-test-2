<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        ]);
    }

    public function edit(Form $form){
        return view($this->viewNamespace.'form', [
            'url' => route('monev.form.update',[$form->id]),
            'item' => $form
        ]);
    }

    public function update(Request $request,Form $form){
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'supervision_start_date' => 'required|date',
            'supervision_end_date' => 'required|date|after_or_equal:supervision_start_date',
            'category' => 'required|string|in:satuan pendidikan,non satuan pendidikan'
        ]);
        $form->update($request->only('name','description','supervision_start_date','supervision_end_date','category'));

        return response()->json([
            'status' => 1,
            'title' => 'Successful!',
            'msg' => 'Data succesfully Updated!'
        ],200);
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'supervision_start_date' => 'required|date',
            'supervision_end_date' => 'required|date|after_or_equal:supervision_start_date',
            'category' => 'required|string|in:satuan pendidikan,non satuan pendidikan'
        ]);
        
        $data = $request->only('name','description','supervision_start_date','supervision_end_date','category');
        $data['created_by'] = auth()->user()->id;
        $newForm = Form::create($data);

        return response()->json([
            'status' => 1,
            'title' => 'Successful!',
            'msg' => 'Data succesfully Created!',
            'item' =>$newForm
        ],200);
    }

    public function destroy(Form $form){
        $form->delete();
        return response()->json([
            'status' => 1,
            'title' => 'Successful!',
            'msg' => 'Data succesfully Deleted!'
        ],200);
    }

    public function data(){
        $data = Form::latest()->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function($row){   
                $link = '<a href="'.route('monev.form.instrument.index',[$row->id]).'">'.strtoupper($row->name).'</a>';     
                return $link;
            })
            ->addColumn('target', function($row){   
                $link = '<button onclick="component(`'.route('monev.form.target.summary',[$row->id]).'`)" class="edit btn btn-success btn-sm">Lihat Sasaran Monitoring</button>';     
                return $link;
            })
            ->addColumn('actions', function($row){   
                $btn = '<div class="list-icons">
                <div class="dropdown">
                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                        <i class="icon-menu9"></i>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="#" class="dropdown-item" onclick="component(`'.route('monev.form.edit',[$row->id]).'`)"><i class="icon-pencil"></i> Edit</a>
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
