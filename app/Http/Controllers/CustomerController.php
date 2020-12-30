<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Storage;
use Validator;
use Carbon\Carbon;
use App\Customer; 
use App\Profile;

class CustomerController extends Controller 
{
public $successStatus = 200;
/** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 


    public function login(Request $request){ 
        $email = $request->input('email');
        $password = $request->input('password');

        $rules = [
            'email' => 'required|email:rfc,dns|max:255',
            'password' => ['required'],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return  response()->json(["message" => $validator->errors()->first()]);
        }


        else if(Customer::where('email',$email)->count() <= 0 ) {
            return response()->json(["message" => "Email number does not exist"],400);
        }
         else{
            $customer = Customer::where('email',$email)->first();

            if(password_verify($password,$customer->password)){
                $customer->save();
                $success=[
                    "id"=>$customer->id,
                    "image"=>$customer->image,
                    "name"=>$customer->name,
                    "email"=>$customer->email
                ];
                $success['token'] = $customer->createToken('Personal Access Token',['customer'])->accessToken; 
                return response()->json(['success' => $success], $this-> successStatus);
            } else {
                return response()->json(['error'=>'Unauthorised'], 401);
            }
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
        $user = Customer::create($input);  
        $success=[
            "id"=>$user->id,
            "image"=>$user->image,
            "name"=>$user->name,
            "email"=>$user->email
        ];
        $success['token'] =  $user->createToken('MyApp')-> accessToken;
        /*$profile=new Profile;
        $profile->user_id=$user->id;
        $profile->save();*/
        return response()->json(['success'=>$success], $this-> successStatus); 
    }

    public function myOrders($id)
    {
        $orders=Customer::find($id)->orders()->get();
        return response()->json(["orders"=>$orders]);
    }

    public function update(Request $request,$id){
        $customer=Customer::find($id);
        $data=[
            "name"=>$request->name,
            "email"=>$request->email
        ];
        if($request->image){
            $image = $request->image;
            $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            \Image::make($image)->resize(420, 240)->save(public_path('storage\images\customers\\').$name); 
            if(Storage::disk('local')->exists('public/images/customers/'.$customer->image->basename)){
                Storage::disk('local')->delete('public/images/customers/'.$customer->image->basename);
            }
            $data["image"]=$name;
        }
        $customer->update($data);
        return response()->json(["success"=>$customer]);

    }

}
