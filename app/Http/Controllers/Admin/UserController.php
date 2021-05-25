<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use DataTables;

class UserController extends Controller
{
    protected $viewNamespace = "pages.admin.management-user.";

    public function index(){
        return view($this->viewNamespace.'index');
    }

    public function create(){
        return view($this->viewNamespace.'form',[
            'url' => route('management-user.store')
        ]);
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email'
        ]);

        User::create(
            $request->only('name','email')
        );

        return response()->json([
            'status' => 1,
            'title' => 'Successful!',
            'msg' => 'Data succesfully created!'
        ],200);
    }

    public function edit(User $user){
        return view($this->viewNamespace.'form',[
            'item' => $user,
            'url' => route('management-user.update',[$user->id])
        ]);
    }

    public function update(Request $request, User $user){
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email'
        ]);

        $user->update(
            $request->only('name','email')
        );

        return response()->json([
            'status' => 1,
            'title' => 'Successful!',
            'msg' => 'Data succesfully updated!'
        ],200);
    }

    public function destroy(User $user){
        $user->delete();

        return response()->json([
            'status' => 1,
            'title' => 'Successful!',
            'msg' => 'Data succesfully deleted!'
        ],200);
    }

    public function data(){
        $data = User::latest()->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('actions', function($row){   
                $btn = '<div class="list-icons">
                <div class="dropdown">
                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                        <i class="icon-menu9"></i>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="#" class="dropdown-item" onclick="component(`'.route('management-user.edit',[$row->id]).'`)"><i class="icon-pencil"></i> Edit</a>
                        <a href="javascript:void(0)" class="dropdown-item" onclick="destroy(`'.route('management-user.destroy',[$row->id]).'`)"><i class="icon-trash"></i> Hapus</a>
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
