<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\EmailNotVerifiedException;
use App\Exceptions\UnauthorizedException;
use App\Http\Requests\TokenRequest;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    public function login(TokenRequest $request)
    {
        $credentials = $request->safe()->only(['email', 'password']);
        if (! Auth::attempt($credentials)) {
            throw new UnauthorizedException();
        }
        
        $user = User::where('email', $request->email)->first();
        if (! $user->hasVerifiedEmail()) {
            throw new EmailNotVerifiedException();
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'token_type' => 'Bearer',
            'access_token' => $token,
            'user' => $user,
        ]);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
}
