<?php


namespace App\Http\Controllers;


use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if(Auth::guard('web')->attempt($credentials)){

            $user = Auth::guard('web')->user();
            $token =  $user->createToken($user->email.'-'.now())->accessToken;

            return response()->json([
                'success' => true,
                'token' => $token
            ], 200);
        }

        return response()->json(['error'=>'Unauthorised'], 401);
    }

    /**
     * Logout logic
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request)
    {
        if($request->user()->token()->revoke()){
            return response()->json([
                'success' => true,
                'message' => 'Successfully logged out'
            ]);
        };

        return response()->json([
            'success' => false,
            'message' => 'Some problem'
        ]);
    }

    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        return response()->json([
            'user' => $user,
            'success' => true,
            'message' => 'Successfully created user!',
        ], 201);
    }

    /**
     * Get auth user
     *
     * @return JsonResponse
     */
    public function details()
    {
        $user = Auth::user();

        return response()->json([
            'success' => true,
            'user' => $user
        ]);
    }
}
