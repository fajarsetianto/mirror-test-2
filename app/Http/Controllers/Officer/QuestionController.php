<?php

namespace App\Http\Controllers\Officer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Instrument;
use App\Models\Pivots\OfficerTarget;
use App\Models\OfficerNote;
use App\Models\UserAnswer;
use Illuminate\Support\Facades\DB;
use DataTables;

class QuestionController extends Controller
{

    protected $viewNamespace = 'pages.officer.inspection.do.question.';

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(OfficerTarget $officerTarget, Instrument $instrument)
    {
        $officerTarget->load(['target.form','target.officers','target.institutionable','officer']);

        return view($this->viewNamespace.'index', [
            'item' => $instrument,
            'officerTarget' => $officerTarget,
            'url' => route('officer.monev.inspection.do.question.store',[$officerTarget->id, $instrument->id]),
        ]);
    }

    

    public function store(Request $request,OfficerTarget $officerTarget, Instrument $instrument)
    {
        $data   = $request->all();
        $userId = auth('officer')->user()->id;
        if($officerTarget->is_leader != 1)
        return response()->json([
            'status' => 0,
            'title' => 'Failed!',
            'msg' => "You're not leader!"
        ],500);

        try{
            DB::beginTransaction();
            $arr = array();
            foreach($instrument->questions()->get() as $key => $row):
                UserAnswer::where('question_id', $row->id)->delete();
                if($row->question_type_id == '1' || $row->question_type_id == '2'):
                    array_push($arr, array(
                        'answer' => $data["answer_$key"],
                        'offered_answer_id' => NULL,
                        'question_id' => $row->id,
                        'respondent_id' => $userId
                    ));
                elseif ($row->question_type_id == '6'):
                    $file = $request->file("file_$key");
                    $fileName = time().'-'.$userId.'-'.$file->getClientOriginalName();
                    array_push($arr, array(
                        'answer' => $fileName,
                        'offered_answer_id' => NULL,
                        'question_id' => $row->id,
                        'respondent_id' => $userId
                    ));
                    $file->move("data_file",$fileName);
                else:
                    $answer = explode("__", $data["answer_option_$key"]);
                    array_push($arr, array(
                        'answer' => $answer[0],
                        'offered_answer_id' => $answer[1],
                        'question_id' => $row->id,
                        'respondent_id' => $userId
                    ));
                endif;
            endforeach;
            UserAnswer::insert($arr);
            DB::commit();
        } catch(\Throwable $throwable){
            DB::rollBack();
            return response()->json([
                'status' => 0,
                'title' => 'Failed!',
                'msg' => 'Data failed Updated!'.$throwable->getMessage()
            ],422);
        }


        return response()->json([
            'status' => 1,
            'title' => 'Successful!',
            'msg' => 'Data succesfully Updated!'
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
}
