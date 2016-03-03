<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('frontend.index');
});

Route::get('/detail', function () {
    return view('frontend.detail');
});

Route::get('/category', function () {
    return view('frontend.category');
});

Route::get('/search', function () {
    return view('frontend.search');
});

Route::get('example/composer', function(){
    return view('example.composer');
});

Route::get('example/paginator', function(){
    $posts = \App\Post::paginate(1);
    //$posts->setPath('custom/url');
    return view('example.paginator', compact('posts'));
});

Route::get('restricted', function(){
    return view('errors.restricted');
});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/


Route::group(['middleware' => 'web'], function () {
    Route::auth();
    Route::get('/admin', 'HomeController@index');
    Route::resource('admin/posts', 'PostsController');
    Route::resource('admin/categories', 'CategoriesController');
    Route::resource('admin/settings', 'SettingsController');
});
