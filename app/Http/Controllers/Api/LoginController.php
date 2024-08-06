<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class LoginController extends Controller
{
    public function __invoke(Request $request)
    {
        // Set validation
        $validator = Validator::make($request->all(), [
            'email'     => 'required|email',
            'password'  => 'required'
        ]);

        // If validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Get credentials from request
        $credentials = $request->only('email', 'password');

        try {
            // Attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email atau Password Anda salah'
                ], 401);
            }
        } catch (JWTException $e) {
            // Something went wrong whilst attempting to encode the token
            return response()->json([
                'success' => false,
                'message' => 'Could not create token'
            ], 500);
        }

        // If auth success
        return response()->json([
            'success' => true,
            // 'user'    => JWTAuth::user(),
            'message' => 'login berhasil',
            'token'   => $token
        ], 200);
    }
}

