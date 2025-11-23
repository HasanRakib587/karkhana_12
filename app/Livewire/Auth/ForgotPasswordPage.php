<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Forgot PassWord | KARKHANA')]
class ForgotPasswordPage extends Component
{
    public $email;

    public function save(){
        $this->validate([
            'email' => 'required|exists:customers,email'
        ]);

        $status = Password::broker('customers')->sendResetLink(['email' => $this->email]);

        if($status === Password::RESET_LINK_SENT){
            session()->flash('success', 'Password Reset link has been sent to your email address');
            $this->email = '';
        }
    }
    public function render()
    {
        return view('livewire.auth.forgot-password-page');
    }
}
