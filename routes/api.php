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
Route::get("getNotifications",'userController@getNotifications')->middleware("auth:api");
Route::get("markAsRead/{id}",'userController@markAsRead')->middleware("auth:api");
Route::get("deleteNotif/{id}",'userController@deleteNotif')->middleware("auth:api");

Route::group( ['middleware' => ['auth:api','scope:admin'] ],function(){
    Route::get('dashboard','CustomerController@dashboard');
 });


Route::post('login/customers', 'CustomerController@login');
Route::post('register/customers', 'CustomerController@register');

Route::get('customers', 'CustomerController@index');
Route::patch('customers/{id}', 'CustomerController@update');
Route::delete('customers/{id}', 'CustomerController@destroy');

Route::post('/password/email', 'ForgotPasswordController@sendResetLinkEmail');
Route::post('/password/reset', 'ResetPasswordController@reset');

Route::post('customer/password/email', 'ForgotPasswordCustomerController@sendResetLinkEmail');
Route::post('customer/password/reset', 'ResetPasswordCustomerController@reset');

Route::resource("categories","CategoryController");
Route::resource("foods","FoodController");
Route::resource("staffs","StaffsController");
Route::get("foods/list/{category}","FoodController@getFood");
Route::post("send",'ContactController@send');
Route::resource("cart",'CartController');
Route::resource("shipping",'ShippingController');
Route::resource("comments",'CommentController');
Route::get("commentsByName/{name}",'CommentController@getComments');
Route::resource("orders",'OrderController'); 
Route::get("myOrders/{id}",'CustomerController@myOrders')->middleware(['auth:api,customer']); 
Route::get("orders/getByState/{state}",'OrderController@getByState');

Route::post("images",'ImageController@image'); 
