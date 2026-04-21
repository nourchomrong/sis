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
        // Validate input
        $this->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Check against temporary credentials
        if ($this->username === $this->tempUser && $this->password === $this->tempPass) {
            // Simulate successful login
            session()->flash('message', 'Login successful!');
            return redirect()->route('dashboard');
        } else {
            // Set error message for invalid credentials
            $this->loginError = 'Invalid username or password.';
        }
    }



    public function render()
    {
        return view('livewire.login');
    }
}