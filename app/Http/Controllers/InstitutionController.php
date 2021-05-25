<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use Illuminate\Http\Request;
use DataTables;

class InstitutionController extends Controller
{

    protected $viewNamespace = "pages.admin.management-sekolah.institution.";

    public function index(){
        return view($this->viewNamespace.'index');
    }

    public function create(){
        return view($this->viewNamespace.'form', [
            'url' => route('school.institution.store'),
        ]);
    }

    public function edit(Institution $institution){
        return view($this->viewNamespace.'form', [
            'url' => route('school.institution.update',[$institution->id]),
            'item' => $institution
        ]);
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|string',
            'npsn' => 'required|string',
            'email' => 'required|string|email',
            'address' => 'required|string',
            'province' => 'required|string',
            'city' => 'required|string',
            'headmaster' => 'required|string',
        ]);

        Institution::create(
            $request->only('type','name','npsn','email','address','province','city','headmaster')
        );

        return response()->json([
            'status' => 1,
            'title' => 'Successful!',
            'msg' => 'Data succesfully Created!',
        ],200);
    }

    public function update(Request $request, Institution $institution){
        $request->validate([
            'name' => 'required|string',
            'npsn' => 'required|string',
            'email' => 'required|string|email',
            'address' => 'required|string',
            'province' => 'required|string',
            'city' => 'required|string',
            'headmaster' => 'required|string',
        ]);

        $institution->update(
            $request->only('type','name','npsn','email','address','province','city','headmaster')
        );

        return response()->json([
            'status' => 1,
            'title' => 'Successful!',
            'msg' => 'Data succesfully Updated!',
        ],200);
    }

    public function destroy(Request $request, Institution $institution){
        $institution->delete();

        return response()->json([
            'status' => 1,
            'title' => 'Successful!',
            'msg' => 'Data succesfully Deleted!',
        ],200);
    }

    public function data(){
        $data = Institution::latest()->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('actions', function($row){   
                $btn = '<div class="list-icons">
                <div class="dropdown">
                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                        <i class="icon-menu9"></i>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="#" class="dropdown-item" onclick="component(`'.route('institution.non-satuan.edit',[$row->id]).'`)"><i class="icon-pencil"></i> Edit</a>
                        <a href="javascript:void(0)" class="dropdown-item" onclick="destroy(`'.route('institution.non-satuan.destroy',[$row->id]).'`)"><i class="icon-trash"></i> Hapus</a>
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
