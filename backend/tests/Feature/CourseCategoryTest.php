<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseCategoryTest extends TestCase
{
	public $structure;

	public function setUp(){
		parent::setup();

		$this->structure = [
			'id',
			'title',
			'icon'
		];
	}

	/* Assertions */
	public function testIndexCourseCategoryAssert(){
		$category = factory(\App\CourseCategory::class)->create();

		$this->assertDatabaseHas('courses_categories', $category->toArray());

		$response 	= $this->json(
			'GET', 
			'/api/course_category'
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

	public function testCreateCourseCategoryAssert(){
		$category = factory(\App\CourseCategory::class)->create();

		$this->assertDatabaseHas('courses_categories', $category->toArray());
		
		$response 	= $this->json(
			'POST', 
			'/api/course_category',
			$category->toArray()
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

	public function testShowCourseCategoryAssert(){
		$category = factory(\App\CourseCategory::class)->create();

		$response 	= $this->json(
			'GET', 
			'/api/course_category/' . $category->id
		);

		$response->assertStatus(200)
		->assertJson([
			'success' => true,
		])
		->assertJsonStructure([
			'success',
			'response',
			'data' => [
				'courses',
				'categories' => $this->structure
			]
		]);
	}

	public function testUpdateCourseCategoryAssert(){
		$category = factory(\App\CourseCategory::class)->create();

		$response 	= $this->json(
			'PUT', 
			'/api/course_category/' . $category->id,
			$category->toArray()
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

	public function testDeleteCourseCategoryAssert(){
		$category = factory(\App\CourseCategory::class)->create();

		$response 	= $this->json(
			'DELETE', 
			'/api/course_category/' . $category->id
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
	public function testCreateCourseCategoryFail(){
		$response 	= $this->json(
			'POST', 
			'/api/course_category',
			[
				'title' => null
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

	public function testShowCourseCategoryFail(){
		$response 	= $this->json(
			'GET', 
			'/api/course_category/9999999'
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

	public function testUpdateCourseCategoryFail(){
		$category 	= factory(\App\CourseCategory::class)->create();

		$response 	= $this->json(
			'PUT', 
			'/api/course_category/' . $category->id,
			[
				'title' 		=> null,
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

	public function testDeleteCourseCategoryFail(){
		$response 	= $this->json(
			'DELETE', 
			'/api/course_category/9999999'
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
