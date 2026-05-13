<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{   
    
    public $username = '';
    public $password = '';
    public $loginError = '';
    public function index()
    {
        return view('auth.login');
    }

   
public function login()
{
    $this->validate([
        'username' => 'required',
        'password' => 'required',
    ]);

    $credentials = [
        'username' => $this->username,
        'password' => $this->password,
    ];

    if (Auth::attempt($credentials)) {

        session()->regenerate();

        // update last login
        auth()->user()->update([
            'last_login' => now()
        ]);

        // get role
        $role = auth()->user()->role->role_type;

        // redirect by role
        if ($role == 'admin') {

            return redirect()->route('admin.dashboard');

        } elseif ($role == 'teacher') {

                return redirect()->route('teachers.dashboard');

        } elseif ($role == 'student') {

            return redirect()->route('students');
        }

        // fallback
        return redirect('/');
    }

    $this->loginError = 'Invalid username or password.';
}



    public function render()
    {
        return view('livewire.login');
    }
}