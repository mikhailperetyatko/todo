<?php

Auth::routes();

Route::get('/debtors/communals/accounts/{account}/relations', 'DebtorAccountController@relations')->middleware('auth')->name('debtors.communals.accounts.relations');
Route::post('/debtors/communals/accounts/{account}/relations', 'DebtorAccountController@relationsStore')->middleware('auth')->name('debtors.communals.accounts.relationsStore');
Route::get('/debtors/communals/accounts/{account}/edit', 'DebtorAccountController@edit')->middleware('auth')->name('debtors.communals.accounts.edit');
Route::patch('/debtors/communals/accounts/{account}', 'DebtorAccountController@update')->middleware('auth')->name('debtors.communals.accounts.update');

Route::get('/test', 'TestController@test')->middleware('auth');
Route::get('/test/suz', 'TestController@suz')->middleware('auth');
Route::get('/test/suv', 'TestController@suv')->middleware('auth');
Route::get('/test/suv2', 'TestController@suv2')->middleware('auth');
Route::get('/test/sum', 'TestController@sum')->middleware('auth');
Route::get('/test2', 'TestController@test2')->middleware('auth');
Route::get('/test5', 'TestController@test3')->middleware('auth');
Route::get('/test4', 'TestController@test4')->middleware('auth');
Route::get('/ps5', function(){
    dd((new \App\Services\PS5\Eldorado())->getStatus());
})->middleware('auth');
Route::get('/test3', function(){
    $mets = new \App\Services\Mets('mihanya@list.ru', 'iVGFJC1DVc');
    var_dump($mets->auth()->getParticipationAmount('250705937', '1'));
    //$state = json_decode(\Redis::get('mets_state_250705937_1'), true, JSON_UNESCAPED_UNICODE);
    //var_dump(\Carbon\Carbon::parse($state[0]['start'])->diffInMinutes(\Carbon\Carbon::now(), true));
})->middleware('auth');
Route::get('/ddu', 'TestController@ddu')->middleware('auth');
//Route::get('/fastclaim/payments', 'TestController@fastclaimPayment');

Route::get('/home/days', 'DayController@index')->middleware('auth');
Route::post('/home/days', 'DayController@store')->middleware('auth');

Route::get('/home/disks/yandex/test', 'YandexDiskController@test')->middleware('auth');
Route::get('/home/disks/yandex/access/{storage}', 'YandexDiskController@access')->middleware('auth');

Route::resource('/home/preinstaller_tasks', 'PreinstallerTaskController')->middleware('auth');
Route::get('/home/disks/yandex/attach', 'YandexDiskController@attach')->middleware('auth');

Route::resource('/home/projects/{project}/markers', 'MarkerController')->middleware('auth');
Route::resource('/home/tags', 'TagController')->middleware('auth');

Route::resource('/home/storages', 'StoragesController')->middleware('auth');
Route::get('/home/storages/{storage}/extend_token', 'StoragesController@extendToken')->middleware('auth');

Route::delete('/home/notifications/{id}', function($id){
    auth()->user()->notifications()->find($id)->delete();
})->middleware('auth');

Route::resource('/home/subtasks/{subtask}/files', 'FilesController')->middleware('auth');


Route::get('/', 'ScheduleController@index')->middleware('auth');
Route::get('/home/teams/{team}/change_owner', 'TeamsController@changeOwner');
Route::post('/home/teams/{team}/assignOwner', 'TeamsController@assignOwner');
Route::get('/home/teams/{team}/users/{user}', 'TeamsController@userSubtasks');

Route::get('/home/schedule', 'ScheduleController@index')->middleware('auth');
Route::get('/home/schedule/calendar', 'ScheduleController@calendar')->middleware('auth');
Route::get('/home/history', 'HistoryController@index')->middleware('auth');

Route::resource('/home/teams', 'TeamsController');

Route::get('/home/projects/{project}/complete', 'ProjectsController@setOld');
Route::get('/home/projects/{project}/resume', 'ProjectsController@setResume');

Route::get('/home/tasks/create', 'TasksController@chooseProject');
Route::resource('/home/projects/{project}/tasks', 'TasksController');
Route::get('/home/projects/{project}/tasks/{task}/subtasks/create', 'SubtasksController@create');
Route::post('/home/projects/{project}/tasks/{task}/subtasks', 'SubtasksController@store');

Route::resource('/home/projects', 'ProjectsController');
Route::get('/home/invites/team/{inviteTeam}', 'InvitesController@joinToTeam')->middleware('auth');

Route::get('/home/subtasks/{subtask}/comments', 'CommentController@index');
Route::post('/home/subtasks/{subtask}/comments', 'CommentController@store');
Route::get('/home/subtasks/{subtask}/comments/{comment}', 'CommentController@show');
Route::patch('/home/subtasks/{subtask}/comments/{comment}', 'CommentController@update');

Route::get('/home/subtasks/{subtask}', 'SubtasksController@show');
Route::get('/home/subtasks/{subtask}/edit', 'SubtasksController@edit');
Route::patch('/home/subtasks/{subtask}', 'SubtasksController@update');
Route::get('/home/subtasks/{subtask}/completing', 'SubtasksController@completing');
Route::patch('/home/subtasks/{subtask}/complete', 'SubtasksController@complete');
Route::get('/home/subtasks/{subtask}/uncompleted', 'SubtasksController@uncompleted');
Route::get('/home/subtasks/{subtask}/finishing', 'SubtasksController@finishing');
Route::patch('/home/subtasks/{subtask}/finish', 'SubtasksController@finish');
Route::get('/home/subtasks/{subtask}/unfinished', 'SubtasksController@unfinished');
Route::delete('/home/subtasks/{subtask}', 'SubtasksController@destroy');