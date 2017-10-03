<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    protected $table = 'users';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected static function boot()
    {
        parent::boot();
        static::deleting(function($content){
            $registrations = $content->registrations;
            foreach($registrations as $registration){
                $registration->delete();
            }
        });
    }

    public function registrations(){
        return $this->hasMany('App\Registration', 'user_id', 'id');
    }
}
