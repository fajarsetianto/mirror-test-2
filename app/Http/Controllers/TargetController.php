<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Institution;
use App\Models\Target;
use Illuminate\Http\Request;
use DataTables;
use TypeError;

class TargetController extends Controller
{
    protected $viewNamespace = "pages.admin.monitoring-evaluasi.form.sasaran-monitoring.";

    public function index(Form $form){
        return view($this->viewNamespace.'index', compact('form'));
    }

    public function create(Form $form){
        return view($this->viewNamespace.'form', [
            'url' => route('monev.form.target.store',[$form->id]),
            'form' => $form,
            'institutions' => Institution::all()
        ]);
    }

    public function edit(Form $form, Target $target){
        return view($this->viewNamespace.'form', [
            'url' => route('monev.form.target.update',[$form->id, $target->id]),
            'form' => $form,
            'institutions' => Institution::all(),
            'item' => $target
        ]);
    }

    public function store(Request $request, Form $form){
        $request->validate([
            'type' => 'required|string|in:responden,petugas MONEV,responden & petugas MONEV',
            'institution_id' => 'required|numeric|exists:institutions,id',
            'officer_id' => 'required_if:type,petugas MONEV|required_if:type,responden & petugas MONEV|numeric|exists:users,id',
        ]);

        $form->targets()->create(
            $request->only('type','institution_id','officer_id')
        );

        return response()->json([
            'status' => 1,
            'title' => 'Successful!',
            'msg' => 'Data succesfully Created!',
        ],200);
    }

    public function update(Request $request, Form $form, Target $target){
        $request->validate([
            'type' => 'required|string|in:responden,petugas MONEV,responden & petugas MONEV',
            'institution_id' => 'required|numeric|exists:institutions,id',
            'officer_id' => 'required_if:type,petugas MONEV|required_if:type,responden & petugas MONEV|numeric|exists:users,id',
        ]);

        $target->update(
            $request->only('type','institution_id','officer_id')
        );

        return response()->json([
            'status' => 1,
            'title' => 'Successful!',
            'msg' => 'Data succesfully Updated!',
        ],200);
    }


    public function destroy(Form $form, Target $target){
        $target->delete();
        return response()->json([
            'status' => 1,
            'title' => 'Successful!',
            'msg' => 'Data succesfully Deleted!',
        ],200);

    }

    public function summary(Form $form){
        $form->load('targets');
        return view($this->viewNamespace.'summary',[
            'form' => $form
        ]);
    }

    public function data(Form $form){
        $data = $form->targets()->latest()->get();
        
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function($row){   
                return $row->nonSatuanPendidikan->name;
            })
            ->addColumn('officer_name', function($row){   
                return $row->officerName();
            })
            ->addColumn('actions', function($row) use ($form){   
                $btn = '<div class="list-icons">
                <div class="dropdown">
                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                        <i class="icon-menu9"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="javascript:void(0)" class="dropdown-item" onclick="component(`'.route('monev.form.target.edit',[$form->id,$row->id]).'`)"><i class="icon-pencil"></i> Edit</a>
                        <a href="javascript:void(0)" class="dropdown-item" onclick="destroy(`'.route('monev.form.target.destroy',[$form->id,$row->id]).'`)"><i class="icon-trash"></i> Hapus</a>
                        <a href="#" class="dropdown-item"><i class="icon-file-excel"></i> Export to .csv</a>
                        <a href="#" class="dropdown-item"><i class="icon-file-word"></i> Export to .doc</a>
                    </div>
                </div>
            </div>';     
                return $btn;
            })
            
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function getInput(Form $form, Target $target){
        return view($this->viewNamespace.'parts.petugas', compact('form','target'));
    }
}
