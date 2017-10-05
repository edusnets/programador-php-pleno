<?php

namespace App\Http\Controllers;

use App\Http\Controllers\RegistrationController;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		//
		$users = User::all();

		return response()->json([
			'success'   => true,
			'response'  => null,
			'data'      => $users->toArray()
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
			'name' 		=> 'required|max:128',
			'email' 	=> 'required|email|unique:users,email|max:128',
			'birthdate' => 'required|date'
		];

		$messages = [
			'name.required'         => 'O campo nome é de preenchimento obrigatório',
			'name.max'   			=> 'O nome pode ter no máximo 128 letras',
			'email.required'        => 'O campo email é de preenchimento obrigatório',
			'email.email'        	=> 'O campo email precisa ser um endereço válido',
			'email.max'  			=> 'O email pode ter no máximo 128 letras',
			'email.unique'  		=> 'Já existe um usuário cadastrado com este email',
			'birthdate.required'  	=> 'A data de nascimento é obrigatória',
			'birthdate.date'  		=> 'A data de nascimento precisa ter um formato válido',
		];

		$validator = Validator::make($request->all(), $rules, $messages);
		
		if($validator->fails()){
			return response()->json([
				'success'	=> false,
				'response'	=> null,
				'data'		=> $validator->errors()->all()
			], 422);
		}

		$user = new User;
		$user->name 		= $request->input('name');
		$user->email 		= $request->input('email');
		$user->birthdate 	= $request->input('birthdate');
		$user->save();

		return response()->json([
			'success'	=> true,
			'response'	=> 'O usuário foi cadastrado com sucesso.',
			'data'		=> $user->toArray()
		], 201);
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
		$user = User::find($id);

		if(empty($user)){
			return response()->json([
				'success'	=> false,
				'response'	=> null,
				'data'		=> 'Usuário não encontrado'
			], 422);
		}

		$registrations 			= $user->registrations;
		$registrationsReturn 	= null;

		if(!empty($registrations)){
			$registrationController = new RegistrationController;
			foreach($registrations as $reg){
				$registrationsReturn[] = $registrationController->createRegistrationObject($reg);
			}
		}

		return response()->json([
			'success'   => true,
			'response'   => null,
			'data'      => [
				'user' => $user->toArray(),
				'registrations' => $registrationsReturn
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
		$user = User::find($id);

		if(empty($user)){
			return response()->json([
				'success'	=> false,
				'response'	=> null,
				'data'		=> 'Usuário não encontrado'
			], 422);
		}

		$rules = [
			'name' 		=> 'required|max:128',
			'birthdate' => 'required|date'
		];

		$messages = [
			'name.required'         => 'O campo nome é de preenchimento obrigatório',
			'name.max'   			=> 'O nome pode ter no máximo 128 letras',
			'birthdate.required'  	=> 'A data de nascimento é obrigatória',
			'birthdate.date'  		=> 'A data de nascimento precisa ter um formato válido',
		];

		$validator = Validator::make($request->all(), $rules, $messages);
		
		if($validator->fails()){
			return response()->json([
				'success'	=> false,
				'response'	=> null,
				'data'		=> $validator->errors()->all()
			], 422);
		}

		$user->name 		= $request->input('name');
		$user->birthdate 	= $request->input('birthdate');
		$user->save();

		return response()->json([
			'success'	=> true,
			'response'	=> 'O usuário foi atualizado com sucesso.',
			'data'		=> $user->toArray()
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
		$user = User::find($id);

		if(empty($user)){
			return response()->json([
				'success'	=> false,
				'response'	=> null,
				'data'		=> 'Usuário não encontrado'
			], 422);
		}

		$user->delete();

		return response()->json([
			'success'	=> true,
			'response'	=> null,
			'data'		=> 'Usuário excluído com sucesso'
		], 200);
	}
}
