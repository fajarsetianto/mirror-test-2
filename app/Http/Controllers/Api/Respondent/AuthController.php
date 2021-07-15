<?php

namespace App\Http\Controllers\Api\Respondent;

use App\Http\Controllers\Controller;
use App\Models\Respondent;
use App\Traits\ApiResponerWrapper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * @group Respondent
 * APIs for autenticate respondent
 */
class AuthController extends Controller
{
    use ApiResponerWrapper;

    /**
     * Login
     * @unauthenticated
     * @responseFile storage/responses/respondent/auth.login.json
     */
    public function login(Request $request)
    {
            $validator = Validator::make($request->all(),[
                'token' => 'required|string',
            ]);
            
            if(!$validator->fails()){
                $respondent = Respondent::where('plain_token', $request->token)->first();
                if (!$respondent || !Hash::check($request->token, $respondent->token)) {
                    return $this->error(
                        'These credentials do not match our records.',
                        401,null
                    );
                }

                return $this->success(
                    [
                        'user' => $respondent,
                        'token' => $respondent->createToken('mobile', ['role:respondent'])->plainTextToken,
                        'token_type' => "Bearer"
                    ],
                    'Logged in Successfully'
                );
            }

            return $this->error(
                'The given data was invalid.', 422, $validator->getMessageBag()
            );
    }
}
