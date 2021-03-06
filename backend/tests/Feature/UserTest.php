<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

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

	/* Assertions */
	public function testIndexUserAssert(){
		$user = factory(\App\User::class)->create();
		$this->assertDatabaseHas('users', $user->toArray());

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

	public function testCreateUserAssert(){
		$user = factory(\App\User::class)->make();

		$response 	= $this->json(
			'POST', 
			'/api/user',
			$user->toArray()
		);

		$response->assertStatus(201)
		->assertJson([
			'success' => true,
		])
		->assertJsonStructure([
			'success',
			'response',
			'data' => $this->structure
		]);
	}

	public function testShowUserAssert(){
		$user = factory(\App\User::class)->create();

		$response 	= $this->json(
			'GET', 
			'/api/user/' . $user->id
		);

		$response->assertStatus(200)
		->assertJson([
			'success' => true,
		])
		->assertJsonStructure([
			'success',
			'response',
			'data' => [
				'user' => $this->structure,
				'registrations'
			]
		]);
	}

	public function testUpdateUserAssert(){
		$user 	= factory(\App\User::class)->create();
		$id 	= $user->id;

		$response 	= $this->json(
			'PUT', 
			'/api/user/' . $id,
			[
				'name' 		=> 'Name Updated',
				'email' 	=> 'email_updated@gmail.com',
				'birthdate' => '2000-02-22'
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

	public function testDeleteUserAssert(){
		$user 	= factory(\App\User::class)->create();
		$id 	= $user->id;

		$response 	= $this->json(
			'DELETE', 
			'/api/user/' . $id
		);

		$response->assertStatus(200)
		->assertJson([
			'success' => true,
		])
		->assertJsonStructure([
			'success',
			'response',
			'data'
		]);
	}

	/* Fails */
	public function testCreateUserFail(){
		$response 	= $this->json(
			'POST', 
			'/api/user',
			[
				'name' => 'Test'
			]
		);

		$response->assertStatus(422)
		->assertJson([
			'success' => false,
		])
		->assertJsonStructure([
			'success',
			'response',
			'data'
		]);
	}

	public function testShowUserFail(){
		$response 	= $this->json(
			'GET', 
			'/api/user/9999999'
		);

		$response->assertStatus(422)
		->assertJson([
			'success' => false,
		])
		->assertJsonStructure([
			'success',
			'response',
			'data'
		]);
	}

	public function testUpdateUserFail(){
		$user 	= factory(\App\User::class)->create();
		$id 	= $user->id;

		$response 	= $this->json(
			'PUT', 
			'/api/user/' . $id,
			[
				//'name' 		=> 'Name Updated',
				'birthdate' => '2000-02-22'
			]
		);

		$response->assertStatus(422)
		->assertJson([
			'success' => false,
		])
		->assertJsonStructure([
			'success',
			'response',
			'data'
		]);
	}

	public function testDeleteUserFail(){
		$response 	= $this->json(
			'DELETE', 
			'/api/user/9999999'
		);

		$response->assertStatus(422)
		->assertJson([
			'success' => false,
		])
		->assertJsonStructure([
			'success',
			'response',
			'data'
		]);
	}
}
