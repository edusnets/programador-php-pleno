<?php

namespace App\Http\Controllers;

use App\Course;
use App\CourseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		//
		$courses 		= Course::all();
		$categories		= CourseCategory::all();

		$coursesReturn 	= null;

		foreach($courses as $course){
			$coursesReturn[] = [
				'id' => $course->id,
				'title' => $course->title,
				'description' => $course->description,
				'category_id' => $course->category_id,
				'category_name' => isset($course->category) ? $course->category->title : null
			];
		}

		return response()->json([
			'success'   => true,
			'message'   => null,
			'data'      => [
				'courses' 		=> $coursesReturn,
				'categories' 	=> $categories->toArray()
			]
		], 200);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		//
		$rules = [
			'title'         => 'required|max:128',
			'description'   => 'required|max:256',
			'category_id'   => 'exists:courses_categories,id'
		];

		$messages = [
			'title.required'        => 'O campo título é de preenchimento obrigatório',
			'title.max'   			=> 'O título pode ter no máximo 128 letras',
			'description.required'	=> 'O campo descrição é de preenchimento obrigatório',
			'description.max'		=> 'A descrição pode ter no máximo 256 letras',
			'category_id.exists'	=> 'Selecione uma categoria válida',
		];

		$validator = Validator::make($request->all(), $rules, $messages);
		
		if($validator->fails()){
			return response()->json([
				'success'   => false,
				'response'  => null,
				'data'      => $validator->errors()->all()
			], 422);
		}

		$course = new Course;
		$course->title 			= $request->input('title');
		$course->description 	= $request->input('description');
		$course->category_id 	= $request->input('category_id');
		$course->save();

		return response()->json([
			'success'   => true,
			'response'  => 'O curso foi cadastrado com sucesso.',
			'data'      => $course->toArray()
		], 200);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		//
		$course 		= Course::find($id);
		$categories		= CourseCategory::all();

		if(empty($course)){
			return response()->json([
				'success'	=> false,
				'response'	=> null,
				'data'		=> 'Curso não encontrado'
			], 422);
		}

		return response()->json([
			'success'   => true,
			'message'   => null,
			'data'      => [
				'course' 		=> $course->toArray(),
				'categories' 	=> $categories->toArray()
			]
		], 200);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		//
		$course = Course::find($id);

		if(empty($course)){
			return response()->json([
				'success'   => false,
				'response'  => null,
				'data'      => 'Curso não encontrado'
			], 422);
		}

		$rules = [
			'title'         	=> 'required|max:128',
			'description'   	=> 'required|max:256',
			'category_id'   	=> 'exists:courses_categories,id'
		];

		$messages = [
			'title.required'        => 'O campo título é de preenchimento obrigatório',
			'title.max'   			=> 'O título pode ter no máximo 128 letras',
			'description.required'	=> 'O campo descrição é de preenchimento obrigatório',
			'description.max'		=> 'A descrição pode ter no máximo 256 letras',
			'category_id.exists'	=> 'Selecione uma categoria válida',
		];

		$validator = Validator::make($request->all(), $rules, $messages);
		
		if($validator->fails()){
			return response()->json([
				'success'   => false,
				'response'  => null,
				'data'      => $validator->errors()->all()
			], 422);
		}

		$course->title 			= $request->input('title');
		$course->description	= $request->input('description');
		$course->category_id	= $request->input('category_id');
		$course->save();

		return response()->json([
			'success'   => true,
			'response'  => 'O curso foi atualizado com sucesso.',
			'data'      => $course->toArray()
		], 200);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		//
		$course = Course::find($id);

		if(empty($course)){
			return response()->json([
				'success'   => false,
				'response'  => null,
				'data'      => 'Curso não encontrado'
			], 422);
		}

		$course->delete();

		return response()->json([
			'success'   => true,
			'response'  => null,
			'data'      => 'Curso excluído com sucesso'
		], 200);
	}

	public function getCategories()
	{
		$categories		= CourseCategory::all();

		return response()->json([
			'success'   => true,
			'message'   => null,
			'data'      => $categories->toArray()
		], 200);
	}
}
