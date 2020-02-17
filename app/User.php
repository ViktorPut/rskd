<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role_id'
    ];

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

    public function role(){
        return $this->belongsTo(Role::class);
    }

    public function houses(){
        return $this->belongsToMany(House::class);
    }

    public function isManager() : bool {
        return $this->role_id == Role::MANAGER ? true : false;
    }

    public function  isAdmin() : bool {
        return $this->role_id == Role::ADMIN ? true : false;
    }

    public static function getManagerList(){
        $managers = User::where('role_id', Role::MANAGER)->get();
        return $managers;
    }

}
