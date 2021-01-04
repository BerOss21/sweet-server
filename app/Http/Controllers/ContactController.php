<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use Validator;

class ContactController extends Controller
{
    public function send(Request $request){
        $validator = Validator::make($request->all(), [
            'name'=>'required',
            'email'=>'required|email',
            'subject'=>'required',
            'msg'=>'required',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()],);
        }
        else{
            Mail::send('contact',[
                'msg'=>$request->msg
            ],function($mail) use($request){
                $mail->from($request->email,$request->name);
                $mail->to('samir@gmail.com')->subject($request->subject);
            });
    
            return response()->json(['success'=>'message envoyé avec succes']);
        }

       }
}
