<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\Instrument;
use App\Models\Question;
use App\Models\QuestionType;
use App\Models\OfferedAnswer;
use Illuminate\Http\Request;
use DataTables;

class QuestionController extends Controller
{
    protected $viewNamespace = "pages.admin.monitoring-evaluasi.form.instrument.question.";

    public function index(Form $form,Instrument $instrument){
        $data = Question::with('offeredAnswer', 'questionType')->where('instrument_id', $instrument->id)->get();
        return view($this->viewNamespace.'index', compact('form','instrument','data'));
    }

    public function create(Form $form, Instrument $instrument){
        return view($this->viewNamespace.'form', [
        ]);
    }

    public function edit(Form $form, Instrument $instrument){
        return view($this->viewNamespace.'form', [
            'url' => route('monev.form.instrument.update',[$form->id, $instrument->id]),
            'item' => $instrument
        ]);
    }

    public function update(Request $request,Form $form, Instrument $instrument,$question){
        $request->validate([
            "question.*"  => "required|string",
            "count_option.*"    => "required|numeric",
            "question_type.*"  => "required|string",
            "question.*"  => "required|string",
            "option_answer.*"  => "required|string",
            "score.*"  => "required|string",
        ]);
        $data = $request->all();
        
        if($question == 0):
            foreach($data['question'] as $key => $row):
                $questionType = QuestionType::where('name', $data['question_type'][$key])->first();
                $question = new Question(array(
                    'content' => $row,
                    'instrument_id' => $instrument->id,
                    'question_type_id' => $questionType->id
                ));
                $question->save();
            endforeach;
        else:
            $question = Question::find($question);
        endif;

        
        foreach($data['question'] as $key => $row):
            $questionType = QuestionType::where('name', $data['question_type'][$key])->first();
            $question->update(array(
                'content' => $row,
                'instrument_id' => $instrument->id,
                'question_type_id' => $questionType->id
            ));
        endforeach;

        $x =0;
        OfferedAnswer::where('question_id', $question->id)->delete();
        foreach($data['count_option'] as $key1 => $countOption):
            $y = 1;
            if(isset($data['option_answer'])):
                for ($i=$x; $i < count($data['option_answer']); $i++) :
                    if($y > $countOption){
                        break;
                    }
                    $offeredAnswer = new OfferedAnswer(array(
                        'value' => $data['option_answer'][$i],
                        'score' => $data['score'][$i],
                        'question_id' => $question->id
                    ));

                    $offeredAnswer->save();
                    $x++; $y++;
                endfor;
            endif;
        endforeach;

        return response()->json([
            'status' => 1,
            'item' => ['question' => $question->id],
            'title' => 'Successful!',
            'msg' => 'Data succesfully updated!'
        ],200);
    }

    public function store(Request $request, Form $form, Instrument $instrument){
        $request->validate([
            "question.*"  => "required|string",
            "count_option.*"    => "required|numeric",
            "question_type.*"  => "required|string",
            "question.*"  => "required|string",
            "option_answer.*"  => "required|string",
            "score.*"  => "required|string",
        ]);
        $data = $request->all();
       
        $questionId = [];
        Question::where('instrument_id', $instrument->id)->delete();
        foreach($data['question'] as $key => $row):
            $questionType = QuestionType::where('name', $data['question_type'][$key])->first();
            $question = new Question(array(
                'content' => $row,
                'instrument_id' => $instrument->id,
                'question_type_id' => $questionType->id
            ));
            $question->save();
            array_push($questionId,$question->id);
        endforeach;

        $x =0;
        foreach($data['count_option'] as $key1 => $countOption):
            $y = 1;
            if(isset($data['option_answer'])):
                for ($i=$x; $i < count($data['option_answer']); $i++) :
                    if($y > $countOption){
                        break;
                    }
                    $offeredAnswer = new OfferedAnswer(array(
                        'value' => $data['option_answer'][$i],
                        'score' => $data['score'][$i],
                        'question_id' => $questionId[$key1]
                    ));

                    $offeredAnswer->save();
                    $x++; $y++;
                endfor;
            endif;
        endforeach;
        

        return response()->json([
            'status' => 1,
            'title' => 'Successful!',
            'msg' => 'Data succesfully Created!'
        ],200);
    }

    public function destroy(Form $form, Instrument $instrument, Question $question){
        $question->delete();

        return response()->json([
            'status' => 1,
            'title' => 'Successful!',
            'msg' => 'Data succesfully deleted!'
        ],200);
    }

    public function data(Form $form){
        $data = $form->instruments()->latest()->get();
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function($row){   
                $link = '<a href="'.route('monev.form.instrument.index',[$row->id]).'">'.strtoupper($row->name).'</a>';     
                return $link;
            })
            ->addColumn('actions', function($row) use ($form){   
                $btn = '<div class="list-icons">
                <div class="dropdown">
                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                        <i class="icon-menu9"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="javascript:void(0)" class="dropdown-item" onclick="component(`edit`,`'.route('monev.form.instrument.edit',[$form->id,$row->id]).'`)"><i class="icon-pencil"></i> Edit</a>
                        <a href="javascript:void(0)" class="dropdown-item" onclick="destroy(`instrument`,`'.route('monev.form.instrument.destroy',[$form->id,$row->id]).'`)"><i class="icon-trash"></i> Hapus</a>
                        <a href="#" class="dropdown-item"><i class="icon-file-excel"></i> Export to .csv</a>
                        <a href="#" class="dropdown-item"><i class="icon-file-word"></i> Export to .doc</a>
                    </div>
                </div>
            </div>';    
                return $btn;
            })
            ->rawColumns(['actions', 'name'])
            ->make(true);
    }

    public function changestatus(Request $request, Form $form, Instrument $instrument){
        $request->validate([
            'status' => 'required|string|in:draft,ready'
        ]);

        $instrument->update($request->only('status'));

        return redirect()->back()->with('message','Instrument telah berhasil ditandai sebagai "'.$request->status.'"');
    }
}
