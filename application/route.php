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

Route::post('/login', 'admin/security/login');

Route::get('admin/index', 'admin/index/index');
Route::post('/v1/admin/img_upload', 'admin/upload/uploadImg');
Route::post('/v1/admin/site_img', 'admin/upload/siteImg');
Route::post('/v1/admin/setting', 'admin/index/setting');
Route::get('/v1/article/get', 'admin/article/get');
Route::get('/v1/article/show', 'admin/article/show');
Route::delete('/v1/article/:id', 'admin/article/delete');
Route::put('/v1/article/:id', 'admin/article/edit');
Route::post('/v1/article', 'admin/article/add');
Route::get('/v1/article/tag/:tname', 'admin/article/getByTag');
Route::get('/v1/article/category/:cname', 'admin/article/getByCatogory');
Route::get('v1/icon', 'admin/icon/get');

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

Route::get('/index/index/index', 'index/index/indexA');
Route::get('/index/index/category/:cname', 'index/index/getByCatogory');
Route::get('/index/index/tag/:tname', 'index/index/getByTag');

Route::post('/index/comment/create', 'index/comment/create');
Route::put('/index/comment/:id', 'index/comment/update');
Route::delete('/index/comment/:id', 'index/comment/delete');
Route::get('/index/comment/:aid', 'index/comment/show');
Route::get('/index/article/:aid', 'index/article/show');
Route::get('/index/article/search', 'index/article/searchByTitle');
Route::get('/qq/login', 'index/oauth/qqLogin');
Route::get('/oauth/qq', 'index/oauth/qqCallback');
Route::get('/oauth/user_info', 'index/oauth/getQQInfo');
Route::get('/', 'index/index/index');
Route::get('/setting', 'index/index/setting');

