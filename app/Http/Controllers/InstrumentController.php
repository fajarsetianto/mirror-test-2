<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Instrument;
use Illuminate\Http\Request;
use DataTables;

class InstrumentController extends Controller
{
    protected $viewNamespace = "pages.admin.monitoring-evaluasi.form.instrument.";

    public function index(Form $form){
        return view($this->viewNamespace.'index', compact('form'));
    }

    public function create(Form $form){
        return view($this->viewNamespace.'form', [
            'url' => route('monev.form.instrument.store',[$form->id]),
        ]);
    }

    public function edit(Form $form, Instrument $instrument){
        return view($this->viewNamespace.'form', [
            'url' => route('monev.form.instrument.update',[$form->id, $instrument->id]),
            'item' => $instrument
        ]);
    }

    public function update(Request $request,Form $form, Instrument $instrument){
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string'
        ]);
        
        $data = $request->only('name','description'); 
        $instrument->update($data);

        return response()->json([
            'status' => 1,
            'title' => 'Successful!',
            'msg' => 'Data succesfully updated!'
        ],200);
    }

    public function store(Request $request, Form $form){
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string'
        ]);
        
        $data = $request->only('name','description'); 
        $newInstrument = new Instrument($data);
        $newInstrument->form()->associate($form);
        $newInstrument->save();

        return response()->json([
            'status' => 1,
            'title' => 'Successful!',
            'msg' => 'Data succesfully Created!'
        ],200);
    }

    public function destroy(Form $form, Instrument $instrument){
        $instrument->delete();

        return response()->json([
            'status' => 1,
            'title' => 'Successful!',
            'msg' => 'Data succesfully deleted!'
        ],200);
    }

    public function data(Form $form){
        $data = $form->instruments()->latest()->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('actions', function($row) use ($form){   
                $btn = '<div class="list-icons">
                <div class="dropdown">
                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                        <i class="icon-menu9"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="javascript:void(0)" class="dropdown-item" onclick="component(`'.route('monev.form.instrument.edit',[$form->id,$row->id]).'`)"><i class="icon-pencil"></i> Edit</a>
                        <a href="javascript:void(0)" class="dropdown-item" onclick="destroy(`instrument`,`'.route('monev.form.instrument.destroy',[$form->id,$row->id]).'`)"><i class="icon-trash"></i> Hapus</a>
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
}
