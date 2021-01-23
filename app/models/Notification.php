<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\DatabaseNotification;
use App\User;

class Notification extends DatabaseNotification
{

    public function getCreatedAtAttribute($value)
    {
        $phpdate = strtotime( $value );
        $mysqldate = date( "F j, Y, g:i a", $phpdate );
        return $mysqldate;
    }
}
