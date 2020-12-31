<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Food extends Model
{
    protected $fillable=["name","description","category_id","price","image","gallery"];
    protected $table="foods";

    public function category(){
        return $this->belongsTo("App\models\Category");
    }

    public function getImageAttribute($value)
    {
       return(Storage::disk('local')->exists('public/images/foods/'.$value))? (\Image::make(public_path()."\\storage\\images\\foods\\".$value)->encode('data-url')):($value);
    }

    public function getGalleryAttribute($value)
    {
       $images= unserialize($value);
       $img=[];
       foreach($images as $image){
            $item=Storage::disk('local')->exists('public/images/gallery/'.$image)? (\Image::make(public_path()."\\storage\\images\\gallery\\".$image)->encode('data-url')):("");
            array_push($img,$item);
       }
       return $img;
       
    }

    public function comments(){
        return $this->hasMany("App\models\Comment");
    }


}
