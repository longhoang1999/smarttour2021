<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use App\Models\User;
use Illuminate\Http\Response;

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
Route::post('login','Api\AuthApiController@login' )->name('api.login');

Route::group(['middleware' => ['auth:sanctum'] ], function(){
    Route::get('get-user/{idUser}', 'Api\UserApiController@getUser')->name('api.getUser');
    Route::get('get-all-user', 'Api\UserApiController@getAllUser')->name('api.getAllUser');
    Route::post('create-user', 'Api\UserApiController@createUser')->name('api.createUser');
    Route::put('update-user/{idUser}', 'Api\UserApiController@updateUser')->name('api.updateUser');
    Route::delete('delete-user/{idUser}', 'Api\UserApiController@deleteUser')->name('api.deleteUser');

    Route::get('logout','Api\AuthApiController@logout' )->name('api.logout');
});
