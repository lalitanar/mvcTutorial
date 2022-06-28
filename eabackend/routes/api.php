<?php

use App\Http\Controllers\EastudentsController;
use App\Http\Controllers\AuthApiController;
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

Route::post('register', [AuthApiController::class, 'register']);
Route::post('login', [AuthApiController::class, 'login']);

Route::middleware('auth:sanctum')->group(function(){
    Route::post('logout', [AuthApiController::class, 'logout']);
    Route::get('user', function(Request $request){
      return $request->user();
    });

    //eastudents APIs Require Token Authentication
    Route::resource('eastudents', EastudentsController::class);
    Route::get('eastudents/major/{major}', [EastudentsController::class, 'major']);
    Route::get('eastudents/faculty/{faculty}', [EastudentsController::class, 'faculty']);

  
}); 

    //eastudents APIs Require Token Authentication
    //Route::resource('eastudents', EastudentsController::class);
    
    //Practice
    //Route::get('eastudents/major/{major}', [EastudentsController::class, 'major']);
    //Route::get('eastudents/faculty/{faculty}', [EastudentsController::class, 'faculty']);





//Route::resource('eastudents', EastudentsController::class);

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });