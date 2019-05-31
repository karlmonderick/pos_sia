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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResources([
    'user' => 'API\UserController',
    'product' => 'API\ProductController',
    'inventory' => 'API\InventoryController',
    'branch' => 'API\BranchController',
    'client' => 'API\ClientController',
    'checkOut' => 'API\CheckOutController',
    'invoice_list' => 'API\InvoiceListController',
]);



Route::get('profile', 'API\UserController@profile');
Route::put('profile', 'API\UserController@updateProfile');
Route::get('findUser', 'API\UserController@search');
Route::get('findBranch', 'API\BranchController@search');
Route::get('findProduct', 'API\ProductController@search');
Route::get('findInventoryProduct', 'API\InventoryController@search');
Route::get('findInventoryCategory', 'API\InventoryController@searchByCategory');
Route::get('searchClient', 'API\ClientController@search');
//Route::get('findInventory', 'API\InventoryController@search');
