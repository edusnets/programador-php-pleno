<?php
namespace Database\Seeder;

use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		//
		$user = new User;
		$user->name = 'John Doe';
		$user->email = str_random(12) . '@gmail.com';
		$user->birthdate = rand(1900, 2000) . '-' . rand(1, 12) . '-' . rand(1, 31);
		$user->save();

		return $user;
	}
}
