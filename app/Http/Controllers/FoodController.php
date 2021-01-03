<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\Food;
use App\models\Category;
use App\Http\Requests\FoodRequest;
use App\Http\Requests\EditFoodRequest;
use Illuminate\Support\Facades\Storage;


class FoodController extends Controller
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
        $foods=Food::with("category")->orderBy("created_at","desc")->get();
        $categories=Category::orderBy("created_at")->get();
        return response()->json(["foods"=>$foods,"categories"=>$categories]);
    }

    public function getFood($category)
    {
        if($category==0){
            $foods=Food::with("category","comments")->orderBy("created_at")->paginate(12);
        }
        else{
            $foods=Food::with("category","comments")->where("category_id",$category)->with("category")->orderBy("created_at")->paginate(12);
        }
        return response()->json(["foods"=>$foods]);
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
    public function store(FoodRequest $request)
    {
        
        $images= $request->gallery;
        $gallery=[];
        $c=0;
       // dd($request->gallery);
        foreach($request->gallery as $value){
            $c++;
            $nameg = $c.time().'.' . explode('/', explode(':', substr($value, 0, strpos($value, ';')))[1])[1];
            array_push($gallery,$nameg);
            \Image::make($value)->resize(420, 240)->save(public_path('storage\images\gallery\\').$nameg);
        }
       // dd($gallery);
        $image = $request->image;
        $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
        \Image::make($image)->resize(420, 240)->save(public_path('storage\images\foods\\').$name);


        $food=Food::create([
            "name"=>$request->name,
            "image"=>$name,
            "gallery"=>serialize($gallery),
            "description"=>$request->description,
            "category_id"=>$request->category,
            "price"=>$request->price
        ]);
        if($food){
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
        $food=Food::where("name",$id)->with("category","comments.customer")->orderBy("created_at","desc")->get();
        return response()->json(["food"=>$food]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $food=Food::find($id);
        return response()->json(["food"=>$food]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditFoodRequest $request, $id)
    {
        $food=Food::find($id);
        $data=[
            "name"=>$request->name,
            "description"=>$request->description,
            "price"=>$request->price,
            "category_id"=>$request->category
        ];

        if($request->image){
            $image = $request->image;
            $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            \Image::make($image)->resize(420, 240)->save(public_path('storage\images\foods\\').$name); 
            if(Storage::disk('local')->exists('public/images/foods/'.$request->img)){
                Storage::disk('local')->delete('public/images/foods/'.$request->img);
            }
            $data["image"]=$name;
        }

        if($request->gallery){
            foreach($food->gallery as $img){
                $basename=gettype($img)=="object"?$img->basename:"";
                if(Storage::disk('local')->exists('public/images/gallery/'.$basename)){
                    Storage::disk('local')->delete('public/images/gallery/'.$basename);
                }
            }
            $images=[];
            $names=[];
            $c=0;
            foreach($request->gallery as $img){
                $c++;
                $name = $c.time().'.' . explode('/', explode(':', substr($img, 0, strpos($img, ';')))[1])[1];
                array_push($names,$name);
                \Image::make($img)->resize(420, 240)->save(public_path('storage\images\gallery\\').$name); 
            }
            $data["gallery"]=serialize($names);
        }
        $food->update($data);
        return response()->json(["success"=>true,"msg"=>"Food updated with success"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $food=Food::find($id);
        $food_img=$food->image->basename?$food->image->basename:"";
        if($food->delete()){ 
            if(Storage::disk('local')->exists('public/images/foods/'.$food_img)){
                Storage::disk('local')->delete('public/images/foods/'.$food_img);
            } 
            foreach($food->gallery as $img){
                $basename=gettype($img)=="object"?$img->basename:"";
                if(Storage::disk('local')->exists('public/images/gallery/'.$basename)){
                    Storage::disk('local')->delete('public/images/gallery/'.$basename);
                }
            }
            return response()->json(["success"=>true,"msg"=>"Food deleted with success"]);
        }
        else{
            return response()->json(["error"=>true,"msg"=>"Food not deleted"]);
        }
    }
}
