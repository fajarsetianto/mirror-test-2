<?php

namespace App\Http\Controllers\Api\Officer;

use App\Http\Controllers\Controller;
use App\Models\Officer;
use App\Traits\ApiResponerWrapper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * @group Officer
 * @authenticated
 * APIs for officer
 */
class FillableController extends Controller
{
    use ApiResponerWrapper;
    /** 
     * Fillables
     * @queryParam limit integer optional
     * @queryParam page integer optional
     * @queryParam keyword string optional
     * @responseFile status=200 storage/responses/officer/fillable.json
     */
    public function index(Request $request)
    {
        $data = auth()->user()
            ->targets()
            ->whereDoesntHave('officers',function($q){
                $q->whereNotNull('officer_targets.submited_at');
            })
            ->whereType('petugas MONEV')
            ->where(function($q) use($request){
                $q->whereHas('form',function($q){
                    $q->published()->valid();
                })
                ->when($request->has('keyword') && $request->keyword != null,function($q) use ($request){
                    $q->where(function($q) use ($request) {
                        $q->whereHas('form',function($q) use ($request){
                            $q->where('name','like', '%'.$request->keyword.'%');
                        })->orWhereHas('institutionable',function($q) use ($request){
                            $q->where('name','like', '%'.$request->keyword.'%');
                        });
                    });
                });
            })
            ->with('form:id,name,supervision_end_date,published_at','institutionable:id,name');
           
        $data = $request->has('limit') && is_numeric($request->limit)
            ? $data->paginate(abs($request->limit)) 
            : $data->paginate(10);
            
        return $this->success(
            $data->withQueryString(),
            'Data successfully loaded'
        );
    }
}
