<?php

namespace  App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\Target;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Validation\Rule;
class TargetController extends Controller
{
    protected $viewNamespace = "pages.admin.monitoring-evaluasi.form.sasaran-monitoring.";

    public function index(Form $form){
        return view($this->viewNamespace.'index', compact('form'));
    }

    public function create(Form $form){
        return view($this->viewNamespace.'form', [
            'url' => route('admin.monev.form.target.store',[$form->id]),
            'form' => $form,
            'select2url' => $form->category == 'satuan pendidikan' ?  route('admin.institution.satuan.select2') : route('admin.institution.non-satuan.select2'),
        ]);
    }

    public function edit(Form $form, Target $target){
        return view($this->viewNamespace.'form', [
            'url' => route('admin.monev.form.target.update',[$form->id, $target->id]),
            'form' => $form,
            'select2url' => $form->category == 'satuan pendidikan' ? route('admin.institution.satuan.select2') : route('admin.institution.non-satuan.select2'),
            'item' => $target
        ]);
    }

    public function store(Request $request, Form $form){
        $request->validate([
            'type' => 'required|string|in:responden,petugas MONEV,responden & petugas MONEV', 
            'institutionable_id' => [
                'required',
                'numeric',
                'exists:'.Target::$institutionalbeClass[$form->category].',id',
                Rule::unique('targets')->where(function($target) use ($form){
                    return $target->whereFormId($form->id)
                        ->whereInstitutionableType(Target::$institutionalbeClass[$form->category]);
                })],
            'officers' => 'required_if:type,petugas MONEV|required_if:type,responden & petugas MONEV|array|min:1',
            'officers.*'=> 'numeric|distinct|exists:users,id',
            'officer_leader' => 'required_if:type,petugas MONEV|required_if:type,responden & petugas MONEV|numeric'.($request->officer != null ? '|in:'.implode(',', $request->officers) : ''),
        ]);

        $newTarget = $form->targets()->make(
            $request->only('type','institutionable_id')
        );
        
        $newTarget['institutionable_type'] = Target::$institutionalbeClass[$form->category];
        $newTarget->save();
        
        if($request->type != 'responden'){
            $newTarget->officers()->sync($request->officers);
            $newTarget->officers()->updateExistingPivot($request->officer_leader,['is_leader' => true]);
        }

        if($request->type == 'responden' || $request->type == 'responden & petugas MONEV'){
            $newToken = sha1(time());
            $newTarget->respondent()->create([
                'token' => Hash::make($newToken),
                'plain_token' => $newToken,
                'target_id' => $newTarget->id
            ]);
        }

        return response()->json([
            'status' => 1,
            'title' => 'Successful!',
            'msg' => 'Data succesfully Created!',
        ],200);
    }

    public function update(Request $request, Form $form, Target $target){
        $request->validate([
            'type' => 'required|string|in:responden,petugas MONEV,responden & petugas MONEV', 
            'institutionable_id' => [
                'required',
                'numeric',
                'exists:'.Target::$institutionalbeClass[$form->category].',id',
                Rule::unique('targets')->where(function($item) use ($form, $target){
                    return $item->whereFormId($form->id)
                        ->where('institutionable_id','<>',$target->institutionable_id)
                        ->whereInstitutionableType(Target::$institutionalbeClass[$form->category]);
                })],
            'officers' => 'required_if:type,petugas MONEV|required_if:type,responden & petugas MONEV|array|min:1',
            'officers.*'=> 'numeric|distinct|exists:users,id',
            'officer_leader' => 'required_if:type,petugas MONEV|required_if:type,responden & petugas MONEV|numeric'.($request->officer != null ? '|in:'.implode(',', $request->officers) : ''),
        ]);
        
        $data = $request->only('type','institutionable_id');
        $data['institutionable_type'] = Target::$institutionalbeClass[$form->category];
        $target->update($data);
        
        $target->officers()->detach();
        if($request->type != 'responden'){
            $target->officers()->sync($request->officers);
            $target->officers()->updateExistingPivot($request->officer_leader,['is_leader' => true]);
        }

        if($request->type == 'responden' || $request->type == 'responden & petugas MONEV' && !$target->respondent()->exists()){
            $newToken = sha1(time());
            $target->respondent()->create([
                'token' => Hash::make($newToken),
                'plain_token' => $newToken,
                'target_id' => $target->id
            ]);
        }

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
        $data = $form->targets()->with('officers','institutionable')->latest();
        
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function($row){   
                return $row->institutionable->name;
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
                        <a href="javascript:void(0)" class="dropdown-item" onclick="component(`'.route('admin.monev.form.target.edit',[$form->id,$row->id]).'`)"><i class="icon-pencil"></i> Edit</a>
                        <a href="javascript:void(0)" class="dropdown-item" onclick="destroy(`'.route('admin.monev.form.target.destroy',[$form->id,$row->id]).'`)"><i class="icon-trash"></i> Hapus</a>
                        <a href="#" class="dropdown-item"><i class="icon-file-excel"></i> Export to .csv</a>
                        <a href="#" class="dropdown-item"><i class="icon-file-word"></i> Export to .doc</a>
                    </div>
                </div>
            </div>';     
                return $form->isEditable() ? $btn : '';
            })
            
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function getInput(Form $form, Target $target){
        $users = auth()->user()->officers;
        return view($this->viewNamespace.'parts.petugas', compact('form','target','users'));
    }
}
