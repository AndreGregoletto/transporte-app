<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function loginView()
    {
        return view('pages.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user  = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            // return response()->json([
            //     'access_token' => $token,
            //     'token_type' => 'Bearer',
            // ]);

            return redirect()->route('dashboard.index')->with('success', 'Login successful!');
        }

        return redirect()->back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
        // return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('loginView')->with('success', 'Logout successful!');
    }
}