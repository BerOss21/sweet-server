<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Food extends Model
{
    protected $fillable=["name","description","category_id","price","image"];
    protected $table="foods";

    public function category(){
        return $this->belongsTo("App\models\Category");
    }

    public function getImageAttribute($value)
    {
       return(Storage::disk('local')->exists('public/images/foods/'.$value))? (\Image::make(public_path()."\\storage\\images\\foods\\".$value)->encode('data-url')):($value);
    }


}
