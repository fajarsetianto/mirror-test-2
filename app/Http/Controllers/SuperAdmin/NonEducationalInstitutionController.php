<?php

namespace  App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\NonEducationalInstitution;
use Illuminate\Http\Request;
use DataTables;

class NonEducationalInstitutionController extends Controller
{

    protected $viewNamespace = "pages.super-admin.management-sekolah.non-educational-institution.";

    public function index(){
        return view($this->viewNamespace.'index');
    }

    public function create(){
        return view($this->viewNamespace.'form', [
            'url' => route('superadmin.institution.non-satuan.store'),
        ]);
    }

    public function edit(NonEducationalInstitution $nonEducationalInstitution){
        return view($this->viewNamespace.'form', [
            'url' => route('superadmin.institution.non-satuan.update',[$nonEducationalInstitution->id]),
            'item' => $nonEducationalInstitution
        ]);
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email',
            'address' => 'required|string',
            'province' => 'required|string',
            'city' => 'required|string',
            'headmaster' => 'required|string',
        ]);

        auth()->user()->institutions()->create(
            $request->only('type','name','npsn','email','address','province','city','headmaster')
        );

        return response()->json([
            'status' => 1,
            'title' => 'Successful!',
            'msg' => 'Data succesfully Created!',
        ],200);
    }

    public function update(Request $request, NonEducationalInstitution $nonEducationalInstitution){
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email',
            'address' => 'required|string',
            'province' => 'required|string',
            'city' => 'required|string',
            'headmaster' => 'required|string',
        ]);

        $nonEducationalInstitution->update(
            $request->only('type','name','npsn','email','address','province','city','headmaster')
        );

        return response()->json([
            'status' => 1,
            'title' => 'Successful!',
            'msg' => 'Data succesfully Updated!',
        ],200);
    }

    public function destroy(Request $request, NonEducationalInstitution $nonEducationalInstitution){
        $nonEducationalInstitution->delete();

        return response()->json([
            'status' => 1,
            'title' => 'Successful!',
            'msg' => 'Data succesfully Deleted!',
        ],200);
    }

    public function data(){
        $data = NonEducationalInstitution::latest();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('actions', function($row){   
                $btn = '<div class="list-icons">
                <div class="dropdown">
                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                        <i class="icon-menu9"></i>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="#" class="dropdown-item" onclick="component(`'.route('superadmin.institution.non-satuan.edit',[$row->id]).'`)"><i class="icon-pencil"></i> Edit</a>
                        <a href="javascript:void(0)" class="dropdown-item" onclick="destroy(`'.route('superadmin.institution.non-satuan.destroy',[$row->id]).'`)"><i class="icon-trash"></i> Hapus</a>
                        <a href="#" class="dropdown-item"><i class="icon-file-word"></i> Export to .doc</a>
                    </div>
                </div>
            </div>';     
                return $btn;
            })
            ->addColumn('createdBy', function($row){   
                return $row->createdBy->name;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function select2(Request $request){
        $data = auth()->user()
                ->institutions()
                ->select('id','name')
                ->when($request->has('search'), function($query) use ($request){
                    $query->where('name','like','%'.$request->search.'%');
                })
                ->paginate(10);
        return response()->json($data);
    }

}
