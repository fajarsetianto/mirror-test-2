<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Officer;
use App\Notifications\Officer\AccountCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use DataTables;
use Illuminate\Support\Facades\Hash;

class OfficerController extends Controller
{
    protected $viewNamespace = "pages.super-admin.officer.";

    public function index(){
        return view($this->viewNamespace.'index');
    }

    public function create(){
        return view($this->viewNamespace.'form',[
            'url' => route('admin.management-user.store')
        ]);
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:officers,email'
        ]);

        $newOfficer = auth()->user()->officers()->make(
            $request->only('name','email')
        );
        $password = Str::random(8);
        $newOfficer->password = Hash::make($password);
        $newOfficer->save();
        $newOfficer->notify(New AccountCreated($password));
        return response()->json([
            'status' => 1,
            'title' => 'Successful!',
            'msg' => 'Data succesfully created!'
        ],200);
    }

    public function edit(Officer $officer){
        return view($this->viewNamespace.'form',[
            'item' => $officer,
            'url' => route('admin.management-user.update',[$officer->id])
        ]);
    }

    public function update(Request $request, Officer $officer){
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:officers,email,'.$officer->id
        ]);

        $officer->update(
            $request->only('name','email')
        );

        return response()->json([
            'status' => 1,
            'title' => 'Successful!',
            'msg' => 'Data succesfully updated!'
        ],200);
    }

    public function destroy(Officer $officer){
        $officer->delete();
        return response()->json([
            'status' => 1,
            'title' => 'Successful!',
            'msg' => 'Data succesfully deleted!'
        ],200);
    }

    public function data(){
        $data = Officer::latest();
        return DataTables::of($data)
            
            ->addIndexColumn()
            ->addColumn('actions', function($row){   
                $btn = '<div class="list-icons">
                <div class="dropdown">
                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                        <i class="icon-menu9"></i>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right">
                    </div>
                </div>
            </div>';     
                return $btn;
            })
            ->addColumn('createdBy', function($row){   
                $btn = $row->createdBy->name;     
                return $btn;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

}
