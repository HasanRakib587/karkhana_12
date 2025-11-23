<?php

namespace App\Models;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{    
    use Notifiable;    
    protected $fillable = [
        "name",
        "phone",
        "email",
        "email_verified_at",
        "password",
        'google_id',
        'facebook_id'
    ];

    public function orders(){
        return $this->hasMany(Order::class);
    }
}
