<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable=["name","phone","address","message","detail","total","state","customer_id"];
    protected $table="orders";

    public function customer(){
        return $this->belongsTo("App\Customer");
    }
    public function getDetailAttribute($value)
    {
       return unserialize($value);
    }

    public function getCreatedAtAttribute($value)
    {
        $phpdate = strtotime( $value );
        $mysqldate = date( "F j, Y, g:i a", $phpdate );
        return $mysqldate;
    }

    public function getStateAttribute($value)
    {
       return ucfirst($value);
    }
}
