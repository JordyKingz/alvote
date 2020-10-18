<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

use JWTAuth;
use Auth;

use App\Models\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
    }

    public function authenticate(Request $request)
    {
        $input = $request->only('email', 'password');
        $token = null;

        if (filter_var($request->get('email'), FILTER_VALIDATE_EMAIL)) {
            //Check for right credentials
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                // User is authenticated. 
                $user = Auth::user();
                if ($user->email_verified_at) {
                    //Create a JWT token for the User
                    if ($token = JWTAuth::attempt(['email' => $request->email, 'password' => $request->password])) {
                        return response()->json([
                            'id' => Auth::user()->id,
                            'name' => Auth::user()->name,
                            'email' => Auth::user()->email,
                            'bearer' => $token,
                        ], 200);
                    } else {
                        return response()->json([
                            'message' => 'Bearer token couldn\'t be created.',
                        ], 400);
                    }
                } else {
                    return response()->json([
                        'message' => 'You need to confirm your account first. Please check your email.',
                    ], 400);
                }
            } else {
                return response()->json([
                    'message' => 'Credentials don\'t match.',
                ], 401);
            }
        } else {
            return response()->json([
                'message' => 'Emailaddress is not valid.',
            ], 400);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function logout(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        try {
            JWTAuth::invalidate($request->token);

            return response()->json([
                'message' => 'User logged out successfully'
            ], 200);
        } catch (JWTException $exception) {
            return response()->json([
                'message' => 'Sorry, the user cannot be logged out'
            ], 500);
        }
    }
}
