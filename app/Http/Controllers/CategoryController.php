<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\Category;
use App\Http\Requests\CategoryRequest;
use App\Http\Requests\EditCategoryRequest;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
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
        $categories=Category::orderBy("created_at")->get();
        return response()->json(["categories"=>$categories]);
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
    public function store(CategoryRequest $request)
    {
        $image = $request->image;
        $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
        \Image::make($image)->save(public_path('storage\images\categories\\').$name);
        $category=Category::create([
            "name"=>$request->name,
            "image"=>$name,
            "description"=>$request->description,
        ]);
        if($category){
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
    public function update(EditCategoryRequest $request, $id)
    {
        $category=Category::find($id);
        $data=[
            "name"=>$request->name,
            "description"=>$request->description,
        ];
        if($request->image){
            $image = $request->image;
            $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            \Image::make($image)->save(public_path('storage\images\categories\\').$name); 
            if(Storage::disk('local')->exists('public/images/categories/'.$request->img)){
                Storage::disk('local')->delete('public/images/categories/'.$request->img);
            }
            $data["image"]=$name;
        }
        $category->update($data);
        return response()->json(["success"=>true,"msg"=>"Category updated with success"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category=Category::find($id);
        $category_img=$category->image->basename?$category->image->basename:"";
        if($category->delete()){ 
            if(Storage::disk('local')->exists('public/images/categories/'.$category_img)){
                Storage::disk('local')->delete('public/images/categories/'.$category_img);
            } 
            return response()->json(["success"=>true,"msg"=>"Category deleted with success"]);
        }
        else{
            return response()->json(["error"=>true,"msg"=>"Category not deleted"]);
        }
    }
}
