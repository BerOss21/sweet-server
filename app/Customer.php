<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Storage;
use App\Scopes\CustomerScope;
use App\Notifications\PasswordResetCustomerNotification;


class Customer extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','image'
    ];

    protected $table="customers";

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function orders(){
        return $this->hasMany("App\models\Order");
    }

    public function comments(){
        return $this->hasMany("App\models\Comment");
    }

    public function getImageAttribute($value)
    {
       return(Storage::disk('local')->exists('public/images/customers/'.$value))? (\Image::make(public_path()."\\storage\\images\\customers\\".$value)->resize(420, 240)->encode('data-url')):($value);
    }

    /*protected static function booted()
    {
        static::addGlobalScope(new CustomerScope);
    }*/

    public function scopeNotAdmin($query){
        return $query->where("name","!=","admin");
    }


    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordResetCustomerNotification($token));
    }


}
