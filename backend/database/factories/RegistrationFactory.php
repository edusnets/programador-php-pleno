<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(\App\Registration::class, function (Faker $faker) {
	$course = factory(\App\Course::class)->create();
	$user 	= factory(\App\User::class)->create();

	return [
		'course_id' 	=> $course->id,
		'user_id' 		=> $user->id
	];
});
