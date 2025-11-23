<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\Customer;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


#[Title('Register | KARKHANA')]
class RegisterPage extends Component
{
    public $name;
    public $email;
    public $password;

    //register customer
    public function save(){
        
        $this->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:customers|max:255',
            'password' => 'required|min:6|max:255'
        ]);

        //save to database
        $customer = Customer::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        Auth::login($customer);

        return redirect()->intended();
    }

    public function render()
    {
        return view('livewire.auth.register-page');
    }
}
