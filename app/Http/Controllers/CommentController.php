<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\Comment;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware(['auth:api'])->only(["destroy","update","store"]);
    }
    public function index()
    {
        $comments=Comment::with("customer")->orderBy("created_at")->get();
        return response()->json(["comments"=>$comments]);
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
        $comment=Comment::create([
            "content"=>$request->content,
            "food_id"=>$request->food_id,
            "customer_id"=>$request->customer_id
        ]);
        if($comment){
            return response()->json(["success"=>true]);
        }

        else{
            return response()->json(["success"=>false]);
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
        $comments=Comment::where("food_id",$id)->with("customer")->orderBy("created_at")->get();
        return response()->json(["comments"=>$comments]);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $comment=Comment::find($id);
        if($comment->delete()){
            return response()->json(["success"=>true]);
        }
        else{
            return response()->json(["success"=>false]);
        }
    }
}
