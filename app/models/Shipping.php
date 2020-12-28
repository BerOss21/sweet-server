<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    protected $fillable=["region","price"];
    protected $table="shipping";
}
