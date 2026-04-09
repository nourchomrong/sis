<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{   
    
    public $username = '';
    public $password = '';
    public $loginError = '';

    // Temporary local credentials
    private $tempUser = 'testuser';
    private $tempPass = 'password123';

    public function index()
    {
        return view('auth.login');
    }
    public function login()
    {
        $this->loginError = '';

        $this->validate([
            'username' => 'required|string',
            'password' => 'required|min:6',
        ]);

        // Temporary local login
        if ($this->username === $this->tempUser && $this->password === $this->tempPass) {
            // Simulate login session
            session(['user' => $this->tempUser]);
            return redirect()->intended('/admin/dashboard');
        }

        // Real database login
        if (Auth::attempt([
            'username' => $this->username,
            'password' => $this->password
        ])) {
            session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        $this->loginError = 'Invalid username or password.';
    }

    public function render()
    {
        return view('livewire.login');
    }
}