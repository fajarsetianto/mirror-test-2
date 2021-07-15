<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Notifications\Officer\NewFormAssigned;
use App\Notifications\Responden\TokenNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DataTables;

class FormController extends Controller
{
    protected $viewNamespace = "pages.admin.monitoring-evaluasi.form.";

    public function index(){
        return view($this->viewNamespace.'index');
    }

    public function create(){
        return view($this->viewNamespace.'form', [
            'url' => route('admin.monev.form.store'),
        ]);
    }

    public function edit(Form $form){
        return view($this->viewNamespace.'form', [
            'url' => route('admin.monev.form.update',[$form->id]),
            'item' => $form
        ]);
    }

    public function update(Request $request,Form $form){
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'supervision_start_date' => 'required|date',
            'supervision_end_date' => 'required|date|after_or_equal:supervision_start_date',
            'category' => 'required|string|in:satuan pendidikan,non satuan pendidikan'
        ]);

        if($form->category != $request->category){
            $form->targets()->delete();
        }

        $form->update($request->only('name','description','supervision_start_date','supervision_end_date','category'));

        return response()->json([
            'status' => 1,
            'title' => 'Successful!',
            'msg' => 'Data succesfully Updated!'
        ],200);
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'supervision_start_date' => 'required|date',
            'supervision_end_date' => 'required|date|after_or_equal:supervision_start_date',
            'category' => 'required|string|in:satuan pendidikan,non satuan pendidikan'
        ]);
        
        $data = $request->only('name','description','supervision_start_date','supervision_end_date','category');
        $data['created_by'] = auth()->user()->id;
        $newForm = Form::create($data);

        return response()->json([
            'status' => 1,
            'title' => 'Successful!',
            'msg' => 'Data succesfully Created!',
            'item' =>$newForm
        ],200);
    }

    public function destroy(Form $form){
        $form->delete();
        return response()->json([
            'status' => 1,
            'title' => 'Successful!',
            'msg' => 'Data succesfully Deleted!'
        ],200);
    }

    public function publish(Form $form){
        $form->load('questions','targets');
        $passed = $form->isPublishable();
        $failed_note = '';
        if(!$passed){
            if(!$form->questions()->exists()){
                $failed_note = "Form in belum memiliki pertanyaan, silahkan tambahkan pertanyaan";
            }elseif($form->instruments()->whereStatus('draft')->exists()){
                $failed_note = "Terdapat group pertanyaan dengan status draf, silahkan periksa kembali";
            }elseif(!$form->targets()->exists()){
                $failed_note = "Form ini belum memiliki sasaran monitoring, silahkan tambahkan sasaran monitoring";
            }else{
                $failed_note = "Tanggal berakhirnya supervisi (".$form->supervision_end_date->format('d-m-Y').") sudah lewat, silahkan periska kembali";
            }
        }
        return view($this->viewNamespace.'publish', compact('form','passed','failed_note'));
    }

    public function publishing(Form $form){
        $form->load(['targets.institutionable','targets.respondent','targets.officers']);
        if($form->isPublishable()){
            $form->update([
                'status' => 'publish',
                'published_at' => Carbon::now()
            ]);
            foreach($form->targets as $target){
                if($target->respondent()->exists()){
                    $target->respondent->notify(new TokenNotification($target));
                }
                foreach($target->officers as $officer){
                    $officer->notify(new NewFormAssigned($target));
                }
            }
            return redirect()->route('admin.monev.form.instrument.index',[$form->id])->with('message' ,'Form telah berhasil di publish');
        }
        return abort(403,'Form is not publishable');
       
    }

    public function data(){
        $data = auth()->user()->forms()->latest();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function($row){   
                $link = '<a href="'.route('admin.monev.form.instrument.index',[$row->id]).'">'.strtoupper($row->name).'</a>';     
                return $link;
            })
            ->addColumn('target', function($row){   
                $link = '<button onclick="component(`'.route('admin.monev.form.target.summary',[$row->id]).'`)" class="edit btn btn-success btn-sm">Lihat Sasaran Monitoring</button>';     
                return $link;
            })
            ->addColumn('actions', function($row){   
                $btn = '<div class="list-icons">
                <div class="dropdown">
                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                        <i class="icon-menu9"></i>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="javascript:void(0)" class="dropdown-item" onclick="component(`'.route('admin.monev.form.edit',[$row->id]).'`)"><i class="icon-pencil"></i> Edit</a>
                        <a href="javascript:void(0)" class="dropdown-item" onclick="destroy(`'.route('admin.monev.form.destroy',[$row->id]).'`)"><i class="icon-trash"></i> Hapus</a>
                        <a href="#" class="dropdown-item"><i class="icon-file-word"></i> Export to .doc</a>
                    </div>
                </div>
            </div>';     
                return $row->isEditable() ? $btn : '';
            })
            ->addColumn('status', function($row){
                $btn = '<span class="badge badge'.($row->status == 'draft' ? '-warning' :  '-primary') .'">'.$row->status.'</span>';     
                return $btn;
            })
            ->rawColumns(['name','target','actions','status'])
            ->make(true);
    }

    
}
