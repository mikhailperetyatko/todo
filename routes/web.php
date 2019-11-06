<?php
/*
Route::get('/', 'PostsController@index');
Route::get('/posts/create', 'PostsController@create');
Route::get('/posts/{data}', 'PostsController@show');
Route::post('/posts', 'PostsController@store');
Route::get('/posts/{data}/edit', 'PostsController@edit');
Route::patch('/posts/{data}', 'PostsController@update');
Route::delete('/posts/{data}', 'PostsController@destroy');
*/
Route::get('/posts/tags/{tag}', 'TagsController@index');
Route::resource('/posts', 'PostsController');
Route::get('/', 'PostsController@index');


Route::view('/contacts', 'contacts');
Route::view('/about', 'about');

Route::post('/feedbacks', 'FeedbacksController@store');
Route::get('/admin/feedbacks', 'FeedbacksController@list');
