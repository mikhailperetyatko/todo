<?php

Route::get('/posts/tags/{tag}', 'TagsController@index');
Route::resource('/posts', 'PostsController');
Route::get('/', 'PostsController@index');

Route::view('/contacts', 'contacts');
Route::view('/about', 'about');

Route::post('/feedbacks', 'FeedbacksController@store');

Route::view('/admin', 'admin')->middleware('auth', 'can:administrate');

Route::get('/admin/feedbacks', 'FeedbacksController@list');

Route::resource('/admin/posts', 'AdminPostsController');

Auth::routes();
