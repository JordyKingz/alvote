<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Models\User;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function accountActivation(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'token' => ['required', 'string', 'max:255'],
        ]);

        $user = User::where('activation_token', $request->token)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Invalid token'
            ], 400);
        }

        $user->email_verified_at = \Carbon\Carbon::now();
        $user->activation_token = null;

        if($user->save()) {
            return response()->json([
                'message' => 'User succesfully activated'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Something went wrong saving your activation link'
            ], 400);
        }
    }
}
