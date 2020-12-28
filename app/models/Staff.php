<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Staff extends Model
{
    protected $fillable=["first_name","last_name","description","image","facebook","instagram","job"];
    protected $table="sttafs";

    public function getImageAttribute($value)
    {
       return(Storage::disk('local')->exists('public/images/staffs/'.$value))?(\Image::make(public_path()."\\storage\\images\\staffs\\".$value)->encode('data-url')):($value);
    }
}
