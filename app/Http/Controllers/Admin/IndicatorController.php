<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Form;
use App\Models\Indicator;
use DataTables;

class IndicatorController extends Controller
{
    protected $viewNamespace = "pages.admin.monitoring-evaluasi.form.indicator.";

    public function create(Form $form){
        return view($this->viewNamespace.'form', [
            'url' => route('monev.form.indicator.create',[$form->id]),
        ]);
    }

    public function edit(Form $form, Indicator $indicator){
        return view($this->viewNamespace.'form', [
            'url' => route('monev.form.indicator.update',[$form->id, $indicator->id]),
            'item' => $indicator
        ]);
    }

    public function store(Request $request, Form $form){
        $request->validate([
            'minimum' => 'required|numeric',
            'maximum' => 'required|numeric|gte:minimum',
            'color' => 'required|string',
            'description' => 'nullable|string',
        ]);
        
        $data = $request->only('minimum','maximum','color','description');
        $newInstrument = new Indicator($data);

        $newInstrument->form()->associate($form);
        $newInstrument->save();

        return response()->json([
            'status' => 1,
            'title' => 'Successful!',
            'msg' => 'Data succesfully Created!'
        ],200);
    }

    public function update(Request $request, Form $form, Indicator $indicator){
        $request->validate([
            'minimum' => 'required|numeric',
            'maximum' => 'required|numeric|gte:minimum',
            'color' => 'required|string',
            'description' => 'nullable|string',
        ]);
        
        $data = $request->only('minimum','maximum','color','description');
        $indicator->update($data);
        return response()->json([
            'status' => 1,
            'title' => 'Successful!',
            'msg' => 'Data succesfully Updated!'
        ],200);
    }

    Public function destroy(Form $form, Indicator $indicator){
        $indicator->delete();
        return response()->json([
            'status' => 1,
            'title' => 'Successful!',
            'msg' => 'Data succesfully Deleted!'
        ],200);
    }

    public function data(Form $form){
        $data = $form->indicators()->latest()->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('color', function($row){   
                $btn = '<div class="sp-preview"><div class="sp-preview-inner" style="background-color: '.$row->color.';"></div></div>';     
                return $btn;
            })
            ->addColumn('actions', function($row) use ($form){   
                $btn = '<div class="list-icons">
                <div class="dropdown">
                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                        <i class="icon-menu9"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="javascript:void(0)" class="dropdown-item" onclick="component(`'.route('monev.form.indicator.edit',[$form->id,$row->id]).'`)"><i class="icon-pencil"></i> Edit</a>
                        <a href="javascript:void(0)" class="dropdown-item" onclick="destroy(`indicator`,`'.route('monev.form.indicator.destroy',[$form->id,$row->id]).'`)"><i class="icon-trash"></i> Hapus</a>
                        <a href="#" class="dropdown-item"><i class="icon-file-excel"></i> Export to .csv</a>
                        <a href="#" class="dropdown-item"><i class="icon-file-word"></i> Export to .doc</a>
                    </div>
                </div>
            </div>';     
                return $btn;
            })
            
            ->rawColumns(['color','actions'])
            ->make(true);
    }
}
