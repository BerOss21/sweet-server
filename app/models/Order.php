<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable=["name","phone","address","message","detail","total","state"];
    protected $table="orders";

    public function getDetailAttribute($value)
    {
       return unserialize($value);
    }
}
