<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    //

	public function user(){
		return $this->hasOne('\App\User', 'id', 'user_id');
	}

	public function course(){
		return $this->hasOne('\App\Course', 'id', 'course_id');
	}
}
