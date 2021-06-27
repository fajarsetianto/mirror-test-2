<?php

namespace  App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EducationalInstitution;
use Illuminate\Http\Request;
use DataTables;

class EducationalInstitutionController extends Controller
{

    protected $viewNamespace = "pages.admin.management-sekolah.educational-institution.";

    public function index(){
        return view($this->viewNamespace.'index');
    }   

    public function data(){
        $data = EducationalInstitution::query();
        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }

    public function select2(Request $request){
        $data = EducationalInstitution::select('id','name')
                ->when($request->has('search'), function($query) use ($request){
                    $query->where('name','like','%'.$request->search.'%')
                        ->orWhere('npsn','like','%'.$request->search.'%');
                    
                })
                ->whereNotNull('email')
                ->paginate(20);
        return response()->json($data);
    }

}
