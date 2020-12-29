<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\Order;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware(['auth:api','scope:admin'])->only(["destroy","update","index"]);
        $this->middleware(['auth:api','scope:customer'])->only(["store"]);
    }
    public function index()
    {
        $orders=Order::orderBy("created_at")->get();
        return response()->json(["orders"=>$orders]);
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
        $order=Order::create([
            "name"=>$request->name,
            "phone"=>$request->phone,
            "address"=>$request->address,
            "message"=>$request->address,
            "total"=>$request->total,
            "detail"=>serialize($request->detail)
        ]);

        if($order){
            return response()->json(["success"=>true]);
        }
        else{
            return response()->json(["success"=>true]);
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
        $order=Order::find($id);
        if($order->update(["state"=>$request->state])){
            return response()->json(["success"=>true]);
        };
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getByState($state){
        $orders=($state=="all")?(Order::orderBy("created_at")->get()):(Order::where("state",$state)->orderBy("created_at")->get());
        return response()->json(["orders"=>$orders]);
    }
}
