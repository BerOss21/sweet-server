<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Category extends Model
{
    protected $fillable=["name","description","image"];
    protected $table="categories";

    public function foods(){
        return $this->hasMany("App\models\Food");
    }

     public function getImageAttribute($value)
    {
       return(Storage::disk('local')->exists('public/images/categories/'.$value))?(\Image::make(public_path()."\\storage\\images\\categories\\".$value)->encode('data-url')):($value);
    }
}
