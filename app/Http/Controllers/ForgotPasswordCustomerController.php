<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Auth;

class ForgotPasswordCustomerController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;


    protected function guard()
{
    return Auth::guard('customer');
}

    public function broker()
    {
     return Password::broker("customers");
    }
    
    protected function sendResetLinkResponse(Request $request, $response)
    {
        return response(['message'=> trans($response)]);

    }


    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return response(['error'=> trans($response)], 422);

    }


}
