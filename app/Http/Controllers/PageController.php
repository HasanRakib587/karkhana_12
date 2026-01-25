<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function contactUs(){
        return view('pages.contact-page');
    }

    public function privacy(){
        return view('pages.privacy-policy-page');
    }

    public function termsConditions(){
        return view('pages.terms-conditiions');
    }
}
