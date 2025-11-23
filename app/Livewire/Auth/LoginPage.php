<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;

#[Title('Login | KARKHANA')]
class LoginPage extends Component
{
    public $email;
    public $password;

    public function save(){

        $this->validate([
            'email' => 'required|email|exists:customers,email',
            'password' => 'required'
        ]);

        if(!Auth::guard('customer')->attempt(['email' => $this->email, 'password' => $this->password])){
            session()->flash('error', 'Invalid Credentials');
            return;
        }

        return redirect()->intended();
    }
    public function render()
    {
        return view('livewire.auth.login-page');
    }
}
