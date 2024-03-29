<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'api_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function roles()
    {   
        return $this->belongsToMany('App\Role', 'user_role', 'user_id', 'role_id');
    }

    public function restaurants()
    {   
        return $this->hasMany('App\Restaurant');
    }

    public function visits()
    {
        return $this->hasMany('App\Visit');
    }

    public function rewards()
    {
        return $this->hasMany('App\Reward');
    }
}
