<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    // Show login form
    public function showLoginForm()
    {
        return view('auth.login'); 
    }

    // Handle login
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|min:6',
        ]);

        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        // Use Laravel Auth to attempt login
        if (Auth::attempt($credentials)) {
            session()->regenerate(); // prevent session fixation
            return response()->json(['success' => true]);
        }

        // If login fails
        return response()->json(['error' => 'Invalid username or password'], 401);
    }

    // Optional: logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}