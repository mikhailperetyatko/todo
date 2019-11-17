<?php

Route::get('/posts/tags/{tag}', 'TagsController@index');
Route::resource('/posts', 'PostsController');
Route::get('/', 'PostsController@index');

Route::view('/contacts', 'contacts');
Route::view('/about', 'about');

Route::post('/feedbacks', 'FeedbacksController@store');
Route::get('/admin/feedbacks', 'FeedbacksController@list');

Route::get('/admin/posts', 'AdminPostsController@index');

Auth::routes();
