<?php

namespace App\Http\Controllers;

use App\Course;
use App\CourseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CourseCategoryController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		//
		$categories		= CourseCategory::with(['courses'])->get();

		return response()->json([
			'success'   => true,
			'message'   => null,
			'data'      => $categories->toArray()
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
			'title'         => 'required',
		];

		$messages = [
			'title.required'	=> 'O campo título é de preenchimento obrigatório',
		];

		$validator = Validator::make($request->all(), $rules, $messages);
		
		if($validator->fails()){
			return response()->json([
				'success'   => false,
				'response'  => null,
				'data'      => $validator->errors()->all()
			], 422);
		}

		$category = new CourseCategory;
		$category->title 		= $request->input('title');
		$category->icon 		= $request->input('icon');
		$category->save();

		return response()->json([
			'success'   => true,
			'response'  => 'A categoria do curso foi cadastrada com sucesso.',
			'data'      => $category->toArray()
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
		$categories		= CourseCategory::find($id);

		if(empty($categories)){
			return response()->json([
				'success'	=> false,
				'response'	=> null,
				'data'		=> 'Categoria não encontrada'
			], 422);
		}

		return response()->json([
			'success'   => true,
			'message'   => null,
			'data'      => [
				'courses' 		=> $categories->courses->toArray(),
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
		$courseCategory = CourseCategory::find($id);

		if(empty($courseCategory)){
			return response()->json([
				'success'   => false,
				'response'  => null,
				'data'      => 'Categoria não encontrada'
			], 422);
		}

		$rules = [
			'title'         	=> 'required',
		];

		$messages = [
			'title.required'        => 'O campo título é de preenchimento obrigatório',
		];

		$validator = Validator::make($request->all(), $rules, $messages);
		
		if($validator->fails()){
			return response()->json([
				'success'   => false,
				'response'  => null,
				'data'      => $validator->errors()->all()
			], 422);
		}

		$courseCategory->title 			= $request->input('title');
		$courseCategory->icon			= $request->input('icon');
		$courseCategory->save();

		return response()->json([
			'success'   => true,
			'response'  => 'A categoria de curso foi atualizada com sucesso.',
			'data'      => $courseCategory->toArray()
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
		$course_category = CourseCategory::find($id);

		if(empty($course_category)){
			return response()->json([
				'success'   => false,
				'response'  => null,
				'data'      => 'Categoria não encontrada'
			], 422);
		}

		$course_category->delete();

		return response()->json([
			'success'   => true,
			'response'  => null,
			'data'      => 'Categoria excluída com sucesso'
		], 200);
	}
}
