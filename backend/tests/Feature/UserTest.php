<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Database\Seeder;
use Database\Seeder\UserSeeder;

class UserTest extends TestCase
{
	public $structure;

	public function setUp(){
		parent::setup();

		$this->structure = [
			'name',
			'email',
			'birthdate',
			'created_at',
			'updated_at'
		];
	}

	public function testIndexUser(){
		$userSeeder 	= new UserSeeder;
		$user			= $userSeeder->run();

		$response 	= $this->json(
			'GET', 
			'/api/user'
		);

		$response->assertStatus(200)
		->assertJson([
			'success' => true,
		])
		->assertJsonStructure([
			'success',
			'response',
			'data' => [$this->structure]
		]);
	}

	public function testCreateUser(){
		$response 	= $this->json(
			'POST', 
			'/api/user',
			[
				'name' => 'John Doe',
				'email' => str_random(12) . '@gmail.com',
				'birthdate' => '1984-03-29'
			]
		);

		$response->assertStatus(200)
		->assertJson([
			'success' => true,
		])
		->assertJsonStructure([
			'success',
			'response',
			'data' => $this->structure
		]);
	}
}
