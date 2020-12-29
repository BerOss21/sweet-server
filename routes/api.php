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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::post('login', 'userController@login');
Route::post('register', 'userController@register');

Route::post('/password/email', 'ForgotPasswordController@sendResetLinkEmail');
Route::post('/password/reset', 'ResetPasswordController@reset');

Route::resource("categories","CategoryController");
Route::resource("foods","FoodController");
Route::resource("staffs","StaffsController");
Route::get("foods/list/{category}","FoodController@getFood");
Route::post("send",'ContactController@send');
Route::resource("cart",'CartController');
Route::resource("shipping",'ShippingController');
Route::resource("orders",'OrderController'); 
Route::get("orders/getByState/{state}",'OrderController@getByState');

