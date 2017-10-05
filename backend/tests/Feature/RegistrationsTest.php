<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationsTest extends TestCase
{
	public $structure;

	public function setUp(){
		parent::setup();

		$this->structure = [
			'id',
			'user',
			'course',
			'date'
		];
	}

	/* Assertions */
	public function testIndexRegistrationAssert(){
		$registration = factory(\App\Registration::class)->create();

		$this->assertDatabaseHas('registrations', $registration->toArray());

		$response 	= $this->json(
			'GET', 
			'/api/registration'
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

	public function testCreateRegistrationAssert(){
		$registration 	= factory(\App\Registration::class)->create();
		$user 			= factory(\App\User::class)->create();
		$course			= factory(\App\Course::class)->create();

		$this->assertDatabaseHas('registrations', $registration->toArray());
		
		$response 	= $this->json(
			'POST', 
			'/api/registration',
			[
				'user_id' => ['id' => $user->id],
				'course_id' => ['id' => $course->id],
			]
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

	public function testShowRegistrationAssert(){
		$registration = factory(\App\Registration::class)->create();

		$response 	= $this->json(
			'GET', 
			'/api/registration/' . $registration->id
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

	public function testDeleteRegistrationAssert(){
		$registration = factory(\App\Registration::class)->create();

		$response 	= $this->json(
			'DELETE', 
			'/api/registration/' . $registration->id
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
	public function testCreateRegistrationFail(){
		$response 	= $this->json(
			'POST', 
			'/api/registration',
			[
				'user_id' => null
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

	public function testShowRegistrationFail(){
		$response 	= $this->json(
			'GET', 
			'/api/registration/9999999'
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

	public function testDeleteRegistrationFail(){
		$response 	= $this->json(
			'DELETE', 
			'/api/registration/9999999'
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
