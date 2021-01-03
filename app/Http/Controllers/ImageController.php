<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function image(){
        return response()->json(["success"=>true],200);
    }
}
