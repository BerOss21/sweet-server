<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable=["content","food_id","customer_id"];
    protected $table="comments";

    public function customer(){
        return $this->belongsTo("App\Customer");
    }

    public function food(){
        return $this->belongsTo("App/models/Food");
    }

    public function getCreatedAtAttribute($value)
    {
        $phpdate = strtotime( $value );
        $mysqldate = date( "d/m/y h:m:s", $phpdate );
        return $mysqldate;
    }
}
