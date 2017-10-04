<?php

use Illuminate\Database\Seeder;

class CourseCategorySeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		//
		DB::table('courses_categories')->insert(
			[
				'title' => 'Biologia',
				'icon' 	=> 'ion-leaf',
			]
		);
		
		DB::table('courses_categories')->insert(
			[
				'title' => 'Física',
				'icon' 	=> 'ion-nuclear',
			]
		);
		
		DB::table('courses_categories')->insert(
			[
				'title' => 'Química',
				'icon' 	=> 'ion-erlenmeyer-flask',
			]
		);

	}
}
