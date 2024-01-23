<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

/**
 * Authentication Routes
 */
Route::post('login', 'AuthController@login');
Route::post('user/store', 'UserController@store');

/**
 * Food entry routes
 */
Route::get('foodentry/reports/{date}', 'FoodEntryController@getAdminReport')->middleware('role:admin');
Route::post('foodentry/user-constraints', 'FoodEntryController@userConstraints')->middleware('role:admin|subscriber');

Route::get('foodentry/all', 'FoodEntryController@index')->middleware('role:admin|subscriber');
Route::post('foodentry/store', 'FoodEntryController@store')->middleware('role:subscriber');
Route::get('foodentry/{id}', 'FoodEntryController@show')->middleware('role:admin|subscriber');
Route::put('foodentry/update', 'FoodEntryController@update')->middleware('role:admin|subscriber');
Route::delete('foodentry/delete/{id}', 'FoodEntryController@destroy')->middleware('role:admin|subscriber');

/**
 * User routes
 */
Route::get('user/all', 'UserController@index')->middleware('role:admin');
Route::get('user/{id}', 'UserController@show')->middleware('role:admin|subscriber');
Route::put('user/update', 'UserController@update')->middleware('role:admin|subscriber');
Route::delete('user/delete/{id}', 'UserController@destroy')->middleware('role:admin|subscriber');

/**
 * User settings routes
 */
Route::get('usersettings/{user_id}', 'UserSettingsController@show')->middleware('role:admin|subscriber');
Route::put('usersettings/updateByUserId', 'UserSettingsController@updateByUserId')->middleware('role:subscriber');
