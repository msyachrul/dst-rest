<?php

namespace App\Http\Controllers;

use App\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => [
                'required',
                'email',
            ],
            'password' => [
                'required',
                'string'
            ],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            throw ValidationException::withMessages(['email' => 'User not found.']);
        }

        if (!Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages(['password' => 'Incorrect password.']);
        }

        $token = JWT::encode([
            'sub' => $user,
            'iat' => time(),
            'exp' => time() + env('JWT_EXPIRED_TIME', 86400),
        ], env('JWT_SECRET'), env('JWT_ALGO', 'HS256'));

        return response()->json(['Authorization' => $token]);
    }
}
