<?php

Auth::routes();


Route::get('/', function () {
	// return view('welcome');
	return redirect('/login') ;
});

Route::get('/main-search-autocomplete', function(){
    return json_encode(DB::table('tasks')->get()->all() );
});

// FOR SUPER ADMIN
Route::get('/subadminmain-search-autocomplete', function(){
	$dep_id = \Auth::user()->dept_id;
    return json_encode(DB::table('tasks')->where('project_id', $dep_id)->get()->all() );
});

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function(){
	// ===================== COMMENTS =====================
	Route::post('/comments/create', 'CommentController@create')->name('create_comment');
	// GET ALL THE USERS FROM AJAX
	Route::post('/users', 'UserController@ajaxuserlist')->name('user_list') ;

	Route::post('/departmentslist', 'ProjectController@list')->name('departments_list') ;
	// ===================== PROJECTS ======================
	Route::get('/projects', 'ProjectController@index')->name('project.show') ;

	Route::get('/projects/create', 'ProjectController@create')->name('project.create') ;

	Route::get('/projects/edit/{id}', 'ProjectController@edit')->name('project.edit') ;

	Route::post('/projects/update/{id}', 'ProjectController@update')->name('project.update') ;

	Route::get('/projects/delete/{id}', 'ProjectController@destroy')->name('project.delete') ;

	// Store the new project from the form posted with the view Above
	Route::post('/projects/store', 'ProjectController@store')->name('project.store');



	// ====================  TASKS =======================
	// Route::get('/tasks','TaskController@index')->name('task.show') ;
	Route::get('/tasks','TaskController@index')->name('task.show') ;
	Route::get('/tasks/view/{id}','TaskController@view')->name('task.view') ;

	// Display the Create Task View form
	Route::get('/tasks/create', 'TaskController@create')->name('task.create'); 

	// Store the new task from the form posted with the view Above
	Route::post('/tasks/store', 'TaskController@store')->name('task.store');

	// Search view
	// Route::get('/tasks/search', 'TaskController@searchTask')->name('task.search');
    // USER TASK SEARCH
    Route::get('tasks/search', 'TaskController@searchTask')->name('task.search') ;

	// Sort Table
	Route::get('/tasks/sort/{key}', 'TaskController@sort')->name('task.sort') ;

	Route::get('/tasks/edit/{id}','TaskController@edit')->name('task.edit');

	Route::get('/tasks/list/{projectid}','TaskController@tasklist')->name('task.list');
	Route::get('/tasks/delete/{id}', 'TaskController@destroy')->name('task.delete') ;
	Route::get('/tasks/deletefile/{id}', 'TaskController@deleteFile')->name('task.deletefile') ;
	Route::post('/tasks/update/{id}', 'TaskController@update')->name('task.update') ;
	Route::get('/tasks/completed/{id}','TaskController@completed')->name('task.completed');

	// =====================  USERS   ============================
	Route::get('/users', 'UserController@index')->name('user.index'); 
	Route::get('/users/list/{id}','UserController@userTaskList')->name('user.list');
	Route::get('/users/create', 'UserController@create')->name('user.create'); 
    Route::post('/users/store', 'UserController@store')->name('user.store'); 
	Route::get('/users/edit/{id}', 'UserController@edit')->name('user.edit'); 
	Route::post('/users/update/{id}', 'UserController@update')->name('user.update') ;
    Route::get('/users/activate/{id}', 'UserController@activate')->name('user.activate') ; 
    Route::get('/users/delete/{id}', 'UserController@destroy')->name('user.delete') ;
    Route::get('/users/disable/{id}', 'UserController@disable')->name('user.disable') ;

});


// =====================  USERS  DASHBORD ============================

Route::get('/userdashbord', 'UserController@userdashbord')->name('user.userdashbord');
Route::get('user/tasks/view/{id}','TaskController@userview')->name('usertask.view') ;
//This Route is used to attain some extratime by the user
Route::get('/tasks/extratime/{id}','TaskController@extratime')->name('task.extratime');
// This route redirect to the user to edit his/her password (provide the view to change the pass)
Route::get('/users/profile/{id}', 'UserController@editpass')->name('userdashbord.edit'); 
//This is used to update the pass of the user
Route::post('/users/passupdate/{id}', 'UserController@passupdate')->name('user.passupdate') ;
// THIS IS USED TO ATTACH A FILE FROM THE USER DASHBOARD
Route::post('/attachfile/{id}', 'TaskController@userfileattach')->name('user.fileattach') ;

// ==================== SUPER ADMIN =================================
Route::group(['prefix' => 'subadmin', 'middleware' => 'auth'], function(){
	Route::get('/tasks','TaskController@subadmintasks')->name('subadmintask.show') ;
	// Display the Create Task View form
	Route::get('/tasks/create', 'TaskController@createtask')->name('subadmintask.create'); 
	// Store the new task from the form posted with the view Above
	Route::post('/tasks/store', 'TaskController@subadminstore')->name('subadmintask.store');
	// ====================  TASKS =======================
	Route::get('/tasks/view/{id}','TaskController@subadminview')->name('subadmintask.view') ;
	Route::get('/tasks/edit/{id}','TaskController@subadminedit')->name('subadmintask.edit');
	Route::post('/tasks/update/{id}', 'TaskController@subadminupdate')->name('subadmintask.update') ;
	// Sort Table
	Route::get('/tasks/sort/{key}', 'TaskController@subadminsort')->name('subadmintask.sort') ;
	// TO SEARCH TASK IN SUBADMIN
	Route::get('tasks/search', 'TaskController@subadminsearchTask')->name('subadmintask.search') ;
});