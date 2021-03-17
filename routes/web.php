<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', 'FrontController@getIndex');
Route::get('/latest-post', 'FrontController@getLatestPost');
Route::get('artical/{slug}', 'FrontController@getArtical');

// Route::get('set-status/{id}', 'AdminPostsController@setStatus');
Route::get('/admin/posts/print', 'AdminPostsController@printPost');
Route::get('/admin/getPhotos/{id?}', 'AdminPostsController@getPhotos')->name('getPhoto');
Route::get('/admin/photos/{id}', 'AdminPostsController@getAddPhoto');
Route::get('/admin/edit-photos/{id}', 'AdminPostsController@getEditPhoto');
Route::put('/admin/update-photos/{id}', 'AdminPostsController@UpdatePhoto');
Route::get('/admin/delete-photos/{id}', 'AdminPostsController@deletePhoto');
Route::post('/admin/photos/add/{id}', 'AdminPostsController@storeAddPhoto');
Route::get('/admin/photos-status-update/{id}', 'AdminPostsController@photoStatusUpdate');
