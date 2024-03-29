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

// Route::get('/', function (Request $request) {
//     return 'hello';
// });



$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [], function($api){
	$api->resource('users', 'App\Http\Controllers\UserController');
	$api->resource('menu_items', 'App\Http\Controllers\MenuItemController');
	$api->resource('orders', 'App\Http\Controllers\OrderController', ['except' => ['update']]);
	$api->resource('visits', 'App\Http\Controllers\VisitController');
	$api->resource('messages', 'App\Http\Controllers\MessageController', ['except' => ['update']]);
	$api->resource('options', 'App\Http\Controllers\MenuOptionController');
	$api->resource('categories', 'App\Http\Controllers\MenuCategoryController');

	$api->get('restaurants/menus/{restaurantId}', 'App\Http\Controllers\RestaurantController@showWithMenus');
	$api->get('visits/orders/{visitId}', 'App\Http\Controllers\VisitController@getVisitOrders');

	$api->post('login', 'App\Http\Controllers\Auth\LoginController@apiLogin');
	// $api->post('orders/take', 'App\Http\Controllers\OrderController@takeOrders');
});

$api->version('v1', ['middleware' => 'auth:api'], function($api){
	$api->resource('restaurants', 'App\Http\Controllers\RestaurantController');
});