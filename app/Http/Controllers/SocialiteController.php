<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Customer;
use Illuminate\Support\Str;
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

    // public function socialAuthentication(){

    //     $googleUser = Socialite::driver('google')->user();        
    //     $customer = Customer::where('google_id', $googleUser->id)->first();

    //     try{
    //         if($customer){
    //         Auth::guard('customer')->login($customer);
    //         return redirect()->intended(route('home'));
    //     }else{
    //         $customerData = Customer::create([
    //             'name' => $googleUser->name,
    //             'email' => $googleUser->email,
    //             'password' => Hash::make('Password@123'),
    //             'google_id' => $googleUser->id,
    //         ]);

    //         if($customerData){
    //             Auth::guard('customer')->login($customerData);
    //             return redirect()->intended(route('home'));
    //         }
    //     }

    //     }catch(Exception $e){
    //         dd($e);
    //     }        
    // }

    public function socialAuthentication(){
        try {
            $googleUser = Socialite::driver('google')
                ->stateless()
                ->user();

            $customer = Customer::updateOrCreate(
                ['google_id' => $googleUser->getId()],
                [
                    'name'     => $googleUser->getName(),
                    'email'    => $googleUser->getEmail(),
                    'password' => Hash::make(Str::random(32)),
                ]
            );

            Auth::guard('customer')->login($customer);

            return redirect()->intended(route('home'));

        } catch (\Throwable $e) {
            logger()->error('Google login failed', [
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->route('login')
                ->with('error', 'Google login failed. Please try again.');
        }
    }
}
