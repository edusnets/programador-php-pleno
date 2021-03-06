<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::resource('user', 'UserController');	
Route::resource('course', 'CourseController');	
Route::resource('course_category', 'CourseCategoryController');	
Route::resource('registration', 'RegistrationController');	
Route::get('dashboard', 'DashboardController@summary');
Route::get('categories', 'CourseController@getCategories');