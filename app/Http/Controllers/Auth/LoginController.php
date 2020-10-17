<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
                //Create a JWT token for the User
                if ($token = JWTAuth::attempt(['email' => $request->email, 'password' => $request->password])) {
                    // User is authenticated. 
                    $user = Auth::user();
                    $user->save();
                    return response()->json([
                        'id' => Auth::user()->id,
                        'name' => Auth::user()->name,
                        'email' => Auth::user()->email,
                        'bearer' => $token,
                    ], 200);
                } else {
                    return response()->json([
                        'message' => 'Het account is nog niet geactiveerd',
                    ], 401);
                }
            } else {
                return response()->json([
                    'message' => 'Deze credentials zijn niet bij ons bekend.',
                ], 401);
            }
        } else {
            return response()->json([
                'message' => 'Geen geldig emailadres',
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
