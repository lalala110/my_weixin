<?php


use think\Route;//引入think的类
Route::get('api/:version/banner/:id','api/:version.Banner/getBanner');

Route::get('api/:version/theme','api/:version.Theme/getSimpleList');
Route::get('api/:version/theme/:id','api/:version.Theme/getComplexOne');

Route::get('api/:version/product/by_category','api/:version.Product/getAllInCatgory');
Route::get('api/:version/product/:id','api/:version.Product/getOne',[],['id'=>'\d+']);
Route::get('api/:version/product/recent','api/:version.Product/getRecent');
Route::get('api/:version/category/all', 'api/:version.Category/getAllCategories');
Route::post('api/:version/token/user', 'api/:version.Token/getToken');
Route::post('api/:version/token/verify', 'api/:version.Token/verifyToken');
Route::post('api/:version/address', 'api/:version.Address/createOrUpdateAddress');
Route::get('api/:version/address', 'api/:version.Address/getUserAddress');
Route::post('api/:version/order', 'api/:version.Order/placeOrder');
Route::post('api/:version/pay/pre_order', 'api/:version.Pay/getPreOrder');