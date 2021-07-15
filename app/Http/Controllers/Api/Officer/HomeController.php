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
class HomeController extends Controller
{
    use ApiResponerWrapper;
    /**
     * Summary
     * @responseFile status=200 storage/responses/officer/summary.json
     */
    public function index(Request $request)
    {
            $query = auth()
                ->user()
                ->targets()
                ->whereHas('form', function($item){
                    $item->published();
                });
            $withRespondentCount = (clone $query)->whereType('responden & petugas MONEV')->count();
            $officerCount = (clone $query)->whereType('petugas MONEV')->count();
            
            return $this->success(
                [
                    'review_count' => $withRespondentCount,
                    'fillable_count' => $officerCount
                ],
                'Data successfully loaded'
            );
    }
}
