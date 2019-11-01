<?php

Route::get('/', 'PostsController@list');
Route::get('/posts/create', 'PostsController@create');
Route::get('/posts/{slug}', 'PostsController@show');
Route::post('/posts', 'PostsController@store');

Route::get('/contacts', function() {return view('contacts');});
Route::get('/about', function() {return view('about');});

Route::post('/feedbacks', 'FeedbacksController@store');
Route::get('/admin/feedbacks', 'FeedbacksController@list');
