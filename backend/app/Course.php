<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Course extends Model
{
    //

	public function category(){
		return $this->hasOne('App\CourseCategory', 'id', 'category_id');
	}

	public function registrations(){
		return $this->hasMany('App\Registration', 'id', 'course_id');
	}
}
