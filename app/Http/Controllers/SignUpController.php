<?php
 
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SignUpController extends Controller
{
    /**
     * Handle an authentication attempt.
     */
    public function sign_up(Request $request): JsonResponse
    {
        $password_pattern = 'regex:/(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])/';

        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'in_game_name' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', $password_pattern],
        ]);

        if ($validator->passes()) {
            $credentials = $validator->validated();
            $user = (new User)->create($credentials);
            Auth::login($user);

            return response()->json([
                'name' => $user->name,
                'in_game_name' => $user->in_game_name
            ]);
        } else {

            // NOTE: below is checking which validation rule failed
            foreach($validator->failed() as $input => $rules){
                $i = 0;
                foreach($rules as $rule => $ruleInfo){
                    $rule = $input.'['.strtolower($rule).']';
                    error_log($rule);
                    $i++;
                }
            }

            // TODO: return better error messages
            return response()->json([
                'error' => 'Fields are invalid',
            ], 422);
        }
 
    }
}