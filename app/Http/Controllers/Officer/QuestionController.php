<?php

namespace App\Http\Controllers\Officer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Instrument;
use App\Models\OfficerAnswer;
use App\Models\Pivots\OfficerTarget;
use App\Models\OfficerNote;
use App\Models\UserAnswer;
use Illuminate\Support\Facades\DB;
use DataTables;

use function PHPSTORM_META\type;

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
        $officerTarget->load(['target.form','target.respondent','target.institutionable','officer']);
        $count = $officerTarget->target->type == 'petugas MONEV' ? 0 : 1;
        
        return view($this->viewNamespace.'index', [
            'item' => $instrument,
            'officerTarget' => $officerTarget,
            'countRespondent' => $count,
            'url' => route('officer.monev.inspection.do.question.store',[$officerTarget->id, $instrument->id])
        ]);
    }

    

    public function store(Request $request,OfficerTarget $officerTarget, Instrument $instrument)
    {
        $data   = $request->all();
        $userId = auth('officer')->user()->id;
        $i=1;
        try{
            DB::beginTransaction();
            foreach($instrument->questions()->get() as $key => $row):
                                
                if($row->question_type_id == '1' || $row->question_type_id == '2'):
                    OfficerAnswer::updateOrCreate(
                        ['question_id'=> $row->id, 'officer_id'=> $userId],
                        [
                            'answer' => $data["answer_$key"],
                            'discrepancy' => empty($data["discrepancy_$key"]) ? '' : $data["discrepancy_$key"],
                            'offered_answer_id' => NULL,
                            'target_id' => $officerTarget->target->id,
                            'question_id' => $row->id,
                            'officer_id' => $userId
                        ]
                    );
                elseif ($row->question_type_id == '6'):
                    if(array_key_exists("file_$key", $data)):
                        $file = $request->file("file_$key");
                        $fileName = time().'-'.$userId.'-'.$file->getClientOriginalName();
                        OfficerAnswer::updateOrCreate(
                            ['question_id'=> $row->id, 'officer_id' => $userId],
                            [
                                'answer' => $fileName,
                                'discrepancy' => empty($data["discrepancy_$key"]) ? '' : $data["discrepancy_$key"],
                                'offered_answer_id' => NULL,
                                'target_id' => $officerTarget->target->id,
                                'question_id' => $row->id,
                                'officer_id' => $userId
                            ]
                        );
                        $file->move("data_file",$fileName);
                    endif;
                elseif($row->question_type_id == '4'):
                    OfficerAnswer::where([
                        ['question_id', $row->id],
                        ['officer_id', $userId]
                    ])->delete();

                    foreach($row->offeredAnswer()->get() as $nm => $checkbox):
                        if(array_key_exists("answer_option_".$key."_".$nm, $data)):
                            $answer = explode("__", $data["answer_option_".$key."_".($nm)]);
                            OfficerAnswer::updateOrCreate(
                                ['question_id'=> $row->id, 'officer_id'=> $userId, 'offered_answer_id' => $answer[1]],
                                [
                                    'answer' => $answer[0],
                                    'discrepancy' => empty($data["discrepancy_$key"]) ? '' : $data["discrepancy_$key"],
                                    'offered_answer_id' => $answer[1],
                                    'target_id' => $officerTarget->target->id,
                                    'question_id' => $row->id,
                                    'officer_id' => $userId
                                ]
                            );
                        endif;
                    endforeach;
                else:
                    if(array_key_exists("answer_option_".$key, $data)):
                        $answer = explode("__", $data["answer_option_$key"]);
                        OfficerAnswer::updateOrCreate(
                            ['question_id'=> $row->id, 'officer_id'=> $userId],
                            [
                                'answer' => $answer[0],
                                'discrepancy' => empty($data["discrepancy_$key"]) ? '' : $data["discrepancy_$key"],
                                'offered_answer_id' => $answer[1],
                                'target_id' => $officerTarget->target->id,
                                'question_id' => $row->id,
                                'officer_id' => $userId
                            ]
                        );
                    endif;
                endif;

                if(array_key_exists("option_other_".$row->id, $data)):
                    if(empty($data["option_other_".$row->id])):
                        OfficerAnswer::where([
                            ['question_id', $row->id],
                            ['officer_id', $userId],
                            ['offered_answer_id', NULL]
                        ])->delete();
                    else:
                        OfficerAnswer::updateOrCreate(
                            ['question_id'=> $row->id, 'officer_id'=> $userId, 'offered_answer_id' => NULL],
                            [
                                'answer' => $data["option_other_".$row->id],
                                'discrepancy' => empty($data["discrepancy_$key"]) ? '' : $data["discrepancy_$key"],
                                'offered_answer_id' => NULL,
                                'target_id' => $officerTarget->target->id,
                                'question_id' => $row->id,
                                'officer_id' => $userId
                            ]
                        );
                    endif;
                endif;
            endforeach;
            
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

    public function show(Request $request, OfficerTarget $officerTarget, Instrument $instrument){
        try{
            $file = $request->get('file');
            $type = $request->get('type');
            if($type == 'officer'):
                $fileName = OfficerAnswer::where([
                    ['answer','like',"%$file%"],
                    ['officer_id', auth('officer')->user()->id]
                ])->first();
            elseif($type == 'respondent'):
                $fileName = UserAnswer::where([
                    ['answer','like',"%$file%"]
                ])->whereHas('question', function($q) use($instrument){
                    $q->where('instrument_id', $instrument->id);
                })->first();
            endif;
        
            $filePath = public_path('data_file/'.$fileName->answer);
            return response()->download($filePath, $file);
        }  catch(\Throwable $throwable){
            return response()->json([
                'status' => 0,
                'title' => 'Failed!',
                'msg' => 'Data Error!'.$throwable->getMessage()
            ],422);
        }
    }
}
