<?php

namespace App\Http\Controllers\Officer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Pivots\OfficerTarget;
use App\Models\OfficerNote;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManagerStatic as Image;
use DataTables;

class DoController extends Controller
{

    protected $viewNamespace = 'pages.officer.inspection.do.';

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(OfficerTarget $officerTarget)
    {
        $officerTarget->load(['target.form','target.officers','target.institutionable','officer']);
        
        $userId = auth('officer')->user()->id;
        $statusSubmit = isset($officerTarget->officerNoteByTarget);
        $statusSameOfficer = isset($officerTarget->officerNoteByTarget) && $officerTarget->officerNoteByTarget->officer_id == $userId;
        return view($this->viewNamespace.'index', [
            'item' => $officerTarget, 
            'status' => $statusSubmit, 
            'statusSameOfficer' => $statusSameOfficer, 
            'url' => route('officer.monev.inspection.do.store',[$officerTarget->id]),
            'urlDownload' => route('officer.monev.inspection.do.show',[$officerTarget->id]),
            'urlSend' => route('officer.monev.inspection.do.send',[$officerTarget->id]),
        ]);
    }

    

    public function store(Request $request,OfficerTarget $officerTarget){
        $this->validate($request, [
            'ipaddr' => 'string|nullable',
            'location' => 'string|nullable',
            'note' => 'required|string',
            'photo_1' => 'image|required|mimes:jpeg,png,jpg,gif',
            'photo_2' => 'image|required|mimes:jpeg,png,jpg,gif',
            'photo_3' => 'image|required|mimes:jpeg,png,jpg,gif',
            'photo_4' => 'image|required|mimes:jpeg,png,jpg,gif',
            'photo_5' => 'image|required|mimes:jpeg,png,jpg,gif',
            'pdf_1' => 'required|mimetypes:application/pdf|max:10000'
        ]);

        $data               = $request->only('ipaddr','location','note','photo_1','photo_2','photo_3','photo_4','photo_5','pdf_1');

        try{
            DB::beginTransaction();
            $arr = array();
            $userId = auth('officer')->user()->id;

            foreach($data as $key => $row):
                if($request->hasFile($key)){
                    OfficerNote::where([
                        ['officer_target_id', $officerTarget->id],
                        ['officer_id', $officerTarget->officer_id], 
                        ['target_id', $officerTarget->target_id],
                        ['type', $key]
                    ])->delete();
                    
                    $file = $request->file($key);
                    $fileName = time().'-'.rand().$userId.'-'.$file->getClientOriginalName();
                    $file->move("data_file_note",$fileName);
                    if (strpos($key, 'photo') !== false) {
                        $img = Image::make(public_path('data_file_note/'.$fileName));
                        $img->save(public_path('data_file_note/'.$fileName,60));
                    }
                    $row = $fileName;
                    array_push($arr, array(
                        'value' => $row,
                        'type' => $key,
                        'officer_target_id' => $officerTarget->id,
                        'officer_id' => $officerTarget->officer_id,
                        'target_id' => $officerTarget->target_id 
                    ));
                }
            endforeach;
            OfficerNote::insert($arr);
            DB::commit();
        } catch(\Throwable $throwable){
            DB::rollBack();
            return response()->json([
                'status' => 0,
                'title' => 'Failed!',
                'msg' => 'Data failed create! '.$throwable->getMessage()
            ],422);
        }


        return response()->json([
            'status' => 1,
            'title' => 'Successful!',
            'msg' => 'Data succesfully create!'
        ],200);
    }

    public function send(Request $request,OfficerTarget $officerTarget){
        try{
            DB::beginTransaction();
            $officerTarget->update(['submited_at' => date('Y-m-d')]);
            $arr = array();
            $userId = auth('officer')->user()->id;
            $data               = $request->only('ipaddr','location','note','photo_1','photo_2','photo_3','photo_4','photo_5','pdf_1');
            foreach($data as $key => $row):
                if($request->hasFile($key)){
                    OfficerNote::where([
                        ['officer_target_id', $officerTarget->id],
                        ['officer_id', $officerTarget->officer_id], 
                        ['target_id', $officerTarget->target_id],
                        ['type', $key]
                    ])->delete();
                    
                    $file = $request->file($key);
                    $fileName = time().'-'.rand().$userId.'-'.$file->getClientOriginalName();
                    $file->move("data_file_note",$fileName);
                    if (strpos($key, 'photo') !== false) {
                        $img = Image::make(public_path('data_file_note/'.$fileName));
                        $img->save(public_path('data_file_note/'.$fileName,60));
                    }
                    $row = $fileName;
                    array_push($arr, array(
                        'value' => $row,
                        'type' => $key,
                        'officer_target_id' => $officerTarget->id,
                        'officer_id' => $officerTarget->officer_id,
                        'target_id' => $officerTarget->target_id 
                    ));
                }
            endforeach;
            OfficerNote::insert($arr);
            DB::commit();
        } catch(\Throwable $throwable){
            DB::rollBack();
            return response()->json([
                'status' => 0,
                'title' => 'Failed!',
                'msg' => 'Data failed create! '.$throwable->getMessage()
            ],422);
        }


        return response()->json([
            'status' => 1,
            'title' => 'Successful!',
            'msg' => 'Data succesfully create!'
        ],200);
    }

    public function show(Request $request,OfficerTarget $officerTarget){
        $file = $request->get('file');
        $userId = auth('officer')->user()->id;
        $fileName = OfficerNote::where([
            ['id', $file],
            ['officer_id', $userId]
        ])->first();
        $filePath = public_path('data_file_note/'.$fileName->value);
        return response()->download($filePath, explode('-',$fileName->value)[2]);
    }

    public function data(OfficerTarget $officerTarget){
        $data = $officerTarget->load('target.form')
                    ->target->form
                    ->instruments();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function($row) use($officerTarget){   
                $link = '<a href="'.route('officer.monev.inspection.do.question.index',[$officerTarget->id,$row->id]).'">'.strtoupper($row->name).'</a>';     
                return $link;
            })
            ->addColumn('question', function($row) use($officerTarget){   
                return $row->questions()->byTargetId($officerTarget->target_id)->count().'/'.$row->questions()->count();
            })
            ->addColumn('status', function($row) use($officerTarget){  
                if($row->questions()->byTargetId($officerTarget->target_id)->count() == $row->questions()->count()){
                    return '<span class="badge badge-success">Lengkap</span>';
                } else {
                    return '<span class="badge badge-danger">Belum Lengkap</span>';
                }
            })
            ->addColumn('actions', function($row){   
                $btn = '<div class="list-icons">
                <div class="dropdown">
                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                        <i class="icon-menu9"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="#" class="dropdown-item"><i class="icon-pencil"></i>Edit</a>
                        <a href="#" class="dropdown-item"><i class="icon-download"></i>Unduh</a>
                    </div>
                </div>
            </div>';    
                return $btn;
            })
            ->rawColumns(['name','status','actions'])
            ->make(true);
    }
}
