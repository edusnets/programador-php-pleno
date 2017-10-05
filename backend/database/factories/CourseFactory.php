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

$factory->define(\App\Course::class, function (Faker $faker) {
	$courseCategory = factory(\App\CourseCategory::class)->create();

	return [
		'title' 		=> $faker->title,
		'description' 	=> $faker->realText(100),
		'category_id' 	=> $courseCategory->id
	];
});
