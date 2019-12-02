<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


Route::get('/clear', ['as'=>'clear','uses'=>'IndexController@clear']);

// index routes
// index routes
Route::get('/', ['as'=>'index.index','uses'=>'IndexController@index']);
Route::get('/home', ['as'=>'index.homeadhoc','uses'=>'IndexController@homeAdhoc']); // reset password redirect adhoc solve
Route::get('/about', ['as'=>'index.about','uses'=>'IndexController@getAbout']);
Route::get('/contact', ['as'=>'index.contact','uses'=>'IndexController@getContact']);
// index routes
// index routes

// blog routes
// blog routes
// Route::resource('blogs','BlogController');
// Route::get('blog/{slug}',['as' => 'blog.single', 'uses' => 'BlogController@getBlogPost']);
// Route::get('blogger/profile/{unique_key}',['as' => 'blogger.profile', 'uses' => 'BlogController@getBloggerProfile']);
// Route::get('/like/{user_id}/{blog_id}',['as' => 'blog.like', 'uses' => 'BlogController@likeBlogAPI']);
// Route::get('/check/like/{user_id}/{blog_id}',['as' => 'blog.checklike', 'uses' => 'BlogController@checkLikeAPI']);
// Route::get('/category/{name}',['as' => 'blog.categorywise', 'uses' => 'BlogController@getCategoryWise']);
// Route::get('/archive/{date}',['as' => 'blog.monthwise', 'uses' => 'BlogController@getMonthWise']);
// blog routes
// blog routes

Route::auth();

// dashboard routes
// dashboard routes
Route::resource('users','UserController');
Route::get('/dashboard', ['as'=>'dashboard.index','uses'=>'DashboardController@index']);

Route::get('/users', ['as'=>'dashboard.users','uses'=>'DashboardController@getUsers']);
Route::get('/users/create', ['as'=>'dashboard.users.create','uses'=>'DashboardController@createUser']);
Route::post('/users/store', ['as'=>'dashboard.users.store','uses'=>'DashboardController@storeUser']);
Route::get('/user/{id}/edit', ['as'=>'dashboard.users.edit','uses'=>'DashboardController@editUser']);
Route::put('/user/{id}/update', ['as'=>'dashboard.users.update','uses'=>'DashboardController@updateUser']);
Route::get('/user/{id}', ['as'=>'dashboard.user.single','uses'=>'DashboardController@getSigleUser']);

Route::get('/upazillas', ['as'=>'dashboard.upazillas','uses'=>'DashboardController@getUpazillas']);
Route::get('/upazilla/{id}/school/list', ['as'=>'dashboard.upazillas.schools','uses'=>'DashboardController@getUpazillaSchools']);

Route::get('/institutes', ['as'=>'dashboard.institutes','uses'=>'DashboardController@getInstitutes']);
Route::get('/institutes/create', ['as'=>'dashboard.institutes.create','uses'=>'DashboardController@createInstitute']);
Route::post('/institutes/store', ['as'=>'dashboard.institutes.store','uses'=>'DashboardController@storeInstitute']);
Route::get('/institutes/{id}/edit', ['as'=>'dashboard.institutes.edit','uses'=>'DashboardController@editInstitute']);
Route::put('/institutes/{id}/update', ['as'=>'dashboard.institutes.update','uses'=>'DashboardController@updateInstitute']);
Route::get('/institute/{device_id}', ['as'=>'dashboard.institute.single','uses'=>'DashboardController@getSingleInstitute']);
Route::get('/institute/user/create/{device_id}', ['as'=>'dashboard.institute.user.create','uses'=>'DashboardController@createInstituteUser']);
Route::post('/institute/user/sote', ['as'=>'dashboard.institute.user.store','uses'=>'DashboardController@storeInstituteUser']);

Route::get('/personal/profile', ['as'=>'dashboard.personal.profile','uses'=>'DashboardController@getPersonalProfile']);
Route::put('/personal/profile/{id}/update', ['as'=>'dashboard.profile.update','uses'=>'DashboardController@updatePersonalProfile']);




// reports
// Route::get('/report/test', ['as'=>'report.test','uses'=>'ReportController@test']);
// Route::get('/report/program/top/sheet/primary', ['as'=>'report.program.topsheetprimary','uses'=>'ReportController@generateProgramTopSheetPrimary']);
// Route::get('/report/program/top/sheet/product', ['as'=>'report.program.topsheetproduct','uses'=>'ReportController@generateProgramTopSheetProduct']);
// Route::get('/report/program/top/sheet/savings', ['as'=>'report.program.topsheetsavings','uses'=>'ReportController@generateProgramTopSheetsavings']);
// Route::get('/report/program/transaction/summary', ['as'=>'report.program.transactionsummary','uses'=>'ReportController@generateTransactionSummary']);
// dashboard routes
// dashboard routes
