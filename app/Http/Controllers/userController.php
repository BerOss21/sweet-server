<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\User; 
use Illuminate\Support\Facades\Auth; 
use Validator;
use App\Profile;
use App\models\Notification;

class userController extends Controller 
{
public $successStatus = 200;
/** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function login(){ 
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('MyApp',["admin"])-> accessToken; 
            //$success['role'] = Auth()->user()->role;
            $success['name'] = Auth()->user()->name;
            $success['id'] =  $user->id;
            return response()->json(['success' => $success], $this-> successStatus); 
        } 
        else{ 
            return response()->json(['error'=>'Unauthorised'], 401); 
        } 
    }
/** 
     * Register api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function register(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            'name' => 'required', 
            'email' => 'required|email', 
            'password' => 'required', 
            'confirm' => 'required|same:password', 
        ]);
        if ($validator->fails()) { 
                    return response()->json(['error'=>$validator->errors()], 401);            
        }
        $input = $request->all(); 
        $input['password'] = bcrypt($input['password']); 
        $user = User::create($input); 
        $success['token'] =  $user->createToken('MyApp')-> accessToken; 
        $success['name'] =  $user->name;
        $success['id'] =  $user->id;
        /*$profile=new Profile;
        $profile->user_id=$user->id;
        $profile->save();*/
        return response()->json(['success'=>$success], $this-> successStatus); 
    }

    public function getNotifications(){
        $user=User::find(1);
        $unread=Notification::whereNull("read_at")->orderBy("created_at")->get();
        $notifications=Notification::orderBy("created_at","desc")->get();

        return response()->json(['notifications'=>$notifications,'unread'=>$unread]);
    }

    public function markAsRead($id){
        $notification=Notification::whereId($id)->first();
        $notification->markAsRead();
        $notifications=Notification::orderBy("created_at","desc")->get();
        $unread=Notification::whereNull("read_at")->orderBy("created_at")->get();

        return response()->json(['notifications'=>$notifications,'unread'=>$unread]);
    }
    
    public function deleteNotif($id){
        $notification=Notification::whereId($id)->first();
        $notification->delete();
        $notifications=Notification::orderBy("created_at","desc")->get();
        $unread=Notification::whereNull("read_at")->orderBy("created_at")->get();
        return response()->json(['notifications'=>$notifications,'unread'=>$unread]);
    }
   
}