<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function summary(){
    	$users 			= \App\User::all()->count();
    	$courses 		= \App\Course::all()->count();
    	$registrations	= \App\Registration::all()->count();

    	return response()->json([
			'success'   => true,
			'message'   => null,
			'data'      => [
				'users' => $users,
				'courses' => $courses,
				'registrations' => $registrations
			]
		], 200);
    }
}
