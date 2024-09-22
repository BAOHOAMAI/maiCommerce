<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Models\User;

class AuthController extends Controller
{
    public function login (Request $request) {
        $data = $request->validate([
            'email'=> ['required', 'email'],
            'password' => 'required',
            'remember' => 'boolean'
        ]);

        $remember = $data['remember'] ?? false;
        unset($data['remember']);

        if (!Auth::attempt($data, $remember)) {
            return response([
                'message' => 'Email or password is incorrect'
            ], 422);
        }
        
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->is_admin) {
            Auth::logout();
            return response([
                'message' => 'You don\'t have permission to authenticate as admin'
            ], 403);
        }

        if (!$user->email_verified_at) {
            Auth::logout();
            return response([
                'message' => 'Your email address is not verified'
            ], 403);
        }

        $token = $user->createToken('main')->plainTextToken;
        return response([
            'user' => new UserResource($user),
            'token' => $token
        ]);
    }
    public function logout()
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();
        $user->tokens()->delete();

        return response('', 204);
    }
    public function getUser(Request $request)
    {
        return new UserResource($request->user());
    }
}
