<?php

namespace App\Http\Controllers\Api\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class AuthController extends Controller
{
    public function login (Request $request) {

        $creds = $request->only(['email', 'password']);

        if (!$token = auth()->attempt($creds)) {
            return response()->json(['error' => 'Incorrect email/password'], 401);
        }
        return response()->json(['token' => $token]);
    }

    public function refresh () {
        try {
            $newToken = auth()->refresh();
        }
        catch (TokenInvalidException $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
        return response()->json(['token' => $newToken]);
    }

    public function register (Request $request) {

        $creds = $request->only(['name', 'email', 'password']);

        if (User::create([
            'name' => $creds['name'],
            'email' => $creds['email'],
            'password' => Hash::make($creds['password'])
        ])) {

            if (!$token = auth()->attempt([
                'email' => $creds['email'],
                'password' => $creds['password']
            ])) {
                return response()->json(['error' => 'Incorrect email/password'], 401);
            }

            return response()->json(['token' => $token]);
            return 'radi';
        }
    }
}
