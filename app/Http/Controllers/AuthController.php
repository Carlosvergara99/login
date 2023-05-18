<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $authUser = Auth::user();
            $user = User::where('email', $request->email)->first();
            $token = $user->createToken('authToken')->plainTextToken;
            return response()->json(['user'=>$authUser ], 200);

        };

        return response()->json(['error' => 'Unauthorized'], 401);
    }


    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $user->save();

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json(['token' => $token], 201);
    }
 
    public function logout(Request $request)
    {
        DB::table('personal_access_tokens')
           ->where('tokenable_id', $request->id)
           ->delete();
        return response()->json(['message' => 'Logged out'], 200);
    }
}
