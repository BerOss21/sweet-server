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
        $foods=Food::where("category_id",$category)->with("category")->orderBy("created_at")->paginate(12);
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
        $image = $request->image;
        $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
        \Image::make($image)->resize(420, 240)->save(public_path('storage\images\foods\\').$name);
        $food=Food::create([
            "name"=>$request->name,
            "image"=>$name,
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
        $food=Food::where("name",$id)->with("category")->orderBy("created_at","desc")->get();
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
            return response()->json(["success"=>true,"msg"=>"Food deleted with success"]);
        }
        else{
            return response()->json(["error"=>true,"msg"=>"Food not deleted"]);
        }
    }
}
