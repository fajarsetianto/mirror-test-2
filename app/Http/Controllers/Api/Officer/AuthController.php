<?php

namespace App\Http\Controllers\Api\Officer;

use App\Http\Controllers\Controller;
use App\Models\Officer;
use App\Traits\ApiResponerWrapper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * @group Officer
 * APIs for autenticate officer
 */
class AuthController extends Controller
{
    use ApiResponerWrapper;

    /**
     * Login
     * @unauthenticated
     * @responseFile storage/responses/officer/auth.login.json
     */
    public function login(Request $request)
    {
            $validator = Validator::make($request->all(),[
                'email' => 'required|email',
                'password' => 'required',
            ]);
            
            if(!$validator->fails()){
                $officer = Officer::where('email', $request->email)->first();
                if (!$officer || !Hash::check($request->password, $officer->password)) {
                    return $this->error(
                        'These credentials do not match our records.',
                        401,null
                    );
                }

                return $this->success(
                    [
                        'user' => $officer,
                        'token' => $officer->createToken('mobile', ['role:officer'])->plainTextToken,
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
