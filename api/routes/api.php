<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\ApiAuthMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('/users',[UserController::class, 'register']);
Route::post('/users/login',[UserController::class, 'login']);

Route::middleware(ApiAuthMiddleware::class)->group(function () {
   Route::get('/users/current', [UserController::class, 'getUserCurrent']); 
   Route::patch('/users/current', [UserController::class, 'update']);
   Route::delete('/users/logout', [UserController::class, 'logout']);

   // contacts
   Route::get('/contacts', [ContactController::class, 'index']);
   Route::post('/contacts', [ContactController::class,'store']);
   Route::get('/contacts/{contact}', [ContactController::class,'show']);
   Route::put('/contacts/{contact}', [ContactController::class,'update']);
   Route::delete('/contacts/{contact}', [ContactController::class,'destroy']);

   // address
   Route::get('/contacts/{contact}/addresses', [AddressController::class, 'index']);
   Route::post('/contacts/{contact}/addresses', [AddressController::class, 'store']);
   Route::get('/contacts/{contact}/addresses/{address}', [AddressController::class, 'show']);
   Route::put('/contacts/{contact}/addresses/{address}', [AddressController::class, 'update']);
   Route::delete('/contacts/{contact}/addresses/{address}', [AddressController::class, 'destroy']);

});