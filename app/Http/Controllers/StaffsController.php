<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\Staff;
use App\Http\Requests\StaffRequest;
use App\Http\Requests\EditStaffRequest;
use Illuminate\Support\Facades\Storage;

class StaffsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware(['auth:api','scope:admin'])->only(["destroy","update","store"]);
    }

    public function index()
    {
        $staffs=Staff::orderBy("created_at")->get();
        return response()->json(["staffs"=>$staffs]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $image = $request->image;
        $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
        \Image::make($image)->resize(420, 240)->save(public_path('storage\images\staffs\\').$name);
        $staff=Staff::create([
            "first_name"=>$request->first_name,
            "last_name"=>$request->last_name, 
            "image"=>$name,
            "description"=>$request->description,
            "facebook"=>$request->facebook,
            "instagram"=>$request->instagram,
            "job"=>$request->job,
        ]);
        if($staff){
            return response()->json(["success"=>"data stored"]);
        }

        else{
            return response()->json(["success"=>"data not stored"]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $staff=Staff::find($id);
        $data=[
            "first_name"=>$request->first_name,
            "last_name"=>$request->last_name,
            "description"=>$request->description,
            "facebook"=>$request->facebook,
            "instagram"=>$request->instagram,
            "job"=>$request->job,
        ];
        if($request->image){
            $image = $request->image;
            $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            \Image::make($image)->resize(420, 240)->save(public_path('storage\images\staffs\\').$name); 
            if(Storage::disk('local')->exists('public/images/staffs/'.$request->img)){
                Storage::disk('local')->delete('public/images/staffs/'.$request->img);
            }
            $data["image"]=$name;
        }
        $staff->update($data);
        return response()->json(["success"=>true,"msg"=>"staff updated with success"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $staff=Staff::find($id);
        $staff_img=$staff->image->basename?$staff->image->basename:"";
        if($staff->delete()){ 
            if(Storage::disk('local')->exists('public/images/staffs/'.$staff_img)){
                Storage::disk('local')->delete('public/images/staffs/'.$staff_img);
            } 
            return response()->json(["success"=>true,"msg"=>"staff deleted with success"]);
        }
        else{
            return response()->json(["error"=>true,"msg"=>"staff not deleted"]);
        }
    }
}
