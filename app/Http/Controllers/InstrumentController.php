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

    public function create(){
        return view($this->viewNamespace.'form', [
            'url' => route('monev.form.store'),
            'action' => 'POST'
        ]);
    }

    public function store(Request $request, Form $form){
        $request->validate([
            'name' => 'required|string|max:1',
            'description' => 'nullable|string'
        ]);
        
        $data = $request->only('name','description');
        $newInstrument = new Instrument($data);
        $newInstrument->form()->associate($form);
        $newInstrument->save();
    }

    public function detail(Form $form){
        return view($this->viewNamespace.'detail');
    }

    public function data(Form $form){
        $data = $form->instruments()->latest()->get();
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('actions', function($row){   
                $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm">View</a>';     
                return $btn;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
}
