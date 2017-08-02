<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;

Route::get('/login', 'admin/security/login');
Route::get('/v1/article/get', 'admin/article/get');
Route::get('/v1/article/show', 'admin/article/show');
Route::delete('/v1/article/:id', 'admin/article/delete');
Route::post('/v1/article', 'admin/article/add');


Route::any('v1/category/get', 'admin/category/getrecursion');
Route::get('v1/category/:id', 'admin/category/get');
Route::post('v1/category', 'admin/category/add');
Route::delete('v1/category/:id', 'admin/category/delete', '', ['id' => '\d+']);
Route::put('v1/category/:id', 'admin/category/edit', '', ['id' => '\d+']);


Route::get('/v1/tag/get', 'admin/tag/get');
Route::delete('/v1/tag/:id', 'admin/tag/delete');
Route::post('/v1/tag', 'admin/tag/add');
Route::put('/v1/tag/:id', 'admin/tag/edit');

Route::any('/v1/login', 'admin/security/login');
Route::post('/v1/logout', 'admin/security/logout');
Route::get('/v1/logined_info', 'admin/security/logined_info');

Route::get('/index/index/index', 'index/index/index');
Route::get('/index/index/category', 'index/index/category');

