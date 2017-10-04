<?php

namespace App\Http\Controllers;

use App\Registration;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegistrationController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		//
		$registrations 		= Registration::all();
		$registrationReturn = [];

		foreach($registrations as $reg){
			$registrationReturn[] = $this->createRegistrationObject($reg);
		}

		return response()->json([
			'success'   => true,
			'message'   => null,
			'data'      => $registrationReturn
		], 200);

		return $registrations;
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
			'user_id.id'		=> 'required|exists:users,id',
			'course_id.id'		=> 'required|exists:courses,id'
		];

		$messages = [
			'user_id.id.required' 		=> 'A identificação do aluno é obrigatória',
			'user_id.id.exists'			=> 'Este aluno não existe',
			'course_id.id.required' 	=> 'A identificação do curso é obrigatória',
			'course_id.id.exists'		=> 'Este curso não existe',
		];

		$validator = Validator::make($request->all(), $rules, $messages);
		
		if($validator->fails()){
			return response()->json([
				'success'   => false,
				'response'  => null,
				'data'      => $validator->errors()->all()
			], 422);
		}

		// search user in the same course
		$search = Registration::where('user_id', '=', $request->input('user_id.id'))->where('course_id', '=', $request->input('course_id.id'))->first();
		if(!empty($search)){
			return response()->json([
				'success'   => false,
				'response'  => null,
				'data'      => ['Este aluno já foi matrículado nesse curso.']
			], 422);
		}

		$registration = new Registration;
		$registration->user_id		= $request->input('user_id.id');
		$registration->course_id	= $request->input('course_id.id');
		$registration->save();

		return response()->json([
			'success'   => true,
			'response'  => 'A matrícula foi efetuada com sucesso.',
			'data'      => $this->createRegistrationObject($registration)
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
		$registration = Registration::find($id);

		if(empty($registration)){
			return response()->json([
				'success'	=> false,
				'response'	=> null,
				'data'		=> 'Matrícula não encontrada'
			], 422);
		}

		return response()->json([
			'success'   => true,
			'message'   => null,
			'data'      => $this->createRegistrationObject($registration)
		], 200);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		//
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
	}

	public function createRegistrationObject($registration)
	{
		$element = [
			'id' 		=> $registration->id,
			'user' 		=> $registration->user,
			'course' 	=> $registration->course()->with(['category'])->first(),
			'date' 		=> Carbon::createFromFormat('Y-m-d G:i:s', $registration->created_at)->format('d/m/Y G:i:s')
		];

		return $element;
	}
}
