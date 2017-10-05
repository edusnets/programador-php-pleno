<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseTest extends TestCase
{
	public $structure;

	public function setUp(){
		parent::setup();

		$this->structure = [
			'id',
			'title',
			'description',
			'category_id',
			'created_at',
			'updated_at'
		];
	}

	/* Assertions */
	public function testIndexCourseAssert(){
		$course = factory(\App\Course::class)->create();

		$this->assertDatabaseHas('courses', $course->toArray());

		$response 	= $this->json(
			'GET', 
			'/api/course'
		);

		$response->assertStatus(200)
		->assertJson([
			'success' => true,
		])
		->assertJsonStructure([
			'success',
			'response',
			'data' => [
				'courses' => [
					$this->structure
				],
				'categories' => []
			]
		]);
	}

	public function testCreateCourseAssert(){
		$course = factory(\App\Course::class)->create();

		$this->assertDatabaseHas('courses', $course->toArray());
		
		$response 	= $this->json(
			'POST', 
			'/api/course',
			$course->toArray()
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

	public function testShowCourseAssert(){
		$course = factory(\App\Course::class)->create();

		$response 	= $this->json(
			'GET', 
			'/api/course/' . $course->id
		);

		$response->assertStatus(200)
		->assertJson([
			'success' => true,
		])
		->assertJsonStructure([
			'success',
			'response',
			'data' => [
				'course' => $this->structure,
				'categories' => [],
				'registrations' => []
			]
		]);
	}

	public function testUpdateCourseAssert(){
		$course = factory(\App\Course::class)->create();

		$response 	= $this->json(
			'PUT', 
			'/api/course/' . $course->id,
			$course->toArray()
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

	public function testDeleteCourseAssert(){
		$course = factory(\App\Course::class)->create();

		$response 	= $this->json(
			'DELETE', 
			'/api/course/' . $course->id
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
	public function testCreateCourseFail(){
		$response 	= $this->json(
			'POST', 
			'/api/course',
			[
				'title' => 'Test'
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

	public function testShowCourseFail(){
		$response 	= $this->json(
			'GET', 
			'/api/course/9999999'
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

	public function testUpdateCourseFail(){
		$course 	= factory(\App\Course::class)->create();

		$response 	= $this->json(
			'PUT', 
			'/api/user/' . $course->id,
			[
				'title' 		=> 'Test',
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

	public function testDeleteCourseFail(){
		$response 	= $this->json(
			'DELETE', 
			'/api/course/9999999'
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
