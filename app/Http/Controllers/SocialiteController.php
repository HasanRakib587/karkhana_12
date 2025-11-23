<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function authProviderRedirect($provider){
        if($provider){
            return Socialite::driver($provider)->redirect();
        }else{
            abort(404);
        }
    }

    public function socialAuthentication(){
        $googleUser = Socialite::driver('google')->user();
        
        $customer = Customer::where('google_id', $googleUser->id)->first();

        try{

            if($customer){
            Auth::guard('customer')->login($customer);
            return redirect()->route('home');
        }else{
            $customerData = Customer::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'password' => Hash::make('Password@123'),
                'google_id' => $googleUser->id,
            ]);

            if($customerData){
                Auth::guard('customer')->login($customerData);
                return redirect()->route('home');
            }
        }

        }catch(Exception $e){
            dd($e);
        }        
    }
}
