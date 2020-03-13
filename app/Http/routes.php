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
Route::get('/users/create/ateo', ['as'=>'dashboard.users.create.ateo','uses'=>'DashboardController@createAteo']);
Route::post('/users/store', ['as'=>'dashboard.users.store','uses'=>'DashboardController@storeUser']);
Route::post('/users/store/ateo', ['as'=>'dashboard.users.store.ateo','uses'=>'DashboardController@storeAteo']);
Route::get('/user/{id}/edit', ['as'=>'dashboard.users.edit','uses'=>'DashboardController@editUser']);
Route::put('/user/{id}/update', ['as'=>'dashboard.users.update','uses'=>'DashboardController@updateUser']);
Route::put('/user/{id}/update/ateo', ['as'=>'dashboard.users.update.ateo','uses'=>'DashboardController@updateAteo']);
Route::get('/user/{id}', ['as'=>'dashboard.user.single','uses'=>'DashboardController@getSingleUser']);
Route::delete('/user/{id}/delete', ['as'=>'dashboard.user.delete','uses'=>'DashboardController@deleteUser']);

Route::get('/teacher/leave/page/{unique_key}/{id}', ['as'=>'dashboard.leavepage','uses'=>'DashboardController@getLeavePage']);
Route::post('/teacher/leave/store', ['as'=>'dashboard.storeleave','uses'=>'DashboardController@storeLeave']);
Route::delete('/teacher/leave/delete/{id}', ['as'=>'dashboard.deleteleave','uses'=>'DashboardController@deleteLeave']);
Route::get('/teacher/leave/list/{device_id}', ['as'=>'dashboard.leavelist','uses'=>'DashboardController@getLeaveList']);

Route::get('/presence/manual/entry/{device_id}', ['as'=>'dashboard.manualentry','uses'=>'DashboardController@getManualEntry']);
Route::post('/teacher/manual/entry/store', ['as'=>'dashboard.storemanualentry','uses'=>'DashboardController@storeManualEntry']);


Route::get('/upazillas', ['as'=>'dashboard.upazillas','uses'=>'DashboardController@getUpazillas']);
Route::get('/upazillas/ateo/{id}', ['as'=>'dashboard.upazillas.ateo','uses'=>'DashboardController@getAteo']);
Route::get('/upazilla/{id}/school/list', ['as'=>'dashboard.upazillas.schools','uses'=>'DashboardController@getUpazillaSchools']);
Route::get('/upazilla/present/list', ['as'=>'dashboard.upazillas.present','uses'=>'DashboardController@getUpazillaSchoolsTeachersPresentList']);
Route::get('/upazilla/absent/list', ['as'=>'dashboard.upazillas.absent','uses'=>'DashboardController@getUpazillaSchoolsTeachersAbsentList']);
Route::get('/upazilla/present/list/{id}', ['as'=>'dashboard.upazillas.present.ateo','uses'=>'DashboardController@getUpazillaSchoolsTeachersPresentListForAteo']);
Route::get('/upazilla/present/list/{id}', ['as'=>'dashboard.upazillas.present.ateo','uses'=>'DashboardController@getUpazillaSchoolsTeachersPresentListForAteo']);
Route::get('/upazilla/absent/list/{id}', ['as'=>'dashboard.upazillas.absent.ateo','uses'=>'DashboardController@getUpazillaSchoolsTeachersAbsentListForAteo']);
Route::post('/upazilla/{id}/update', ['as'=>'dashboard.upazilla.update','uses'=>'DashboardController@updateUpazilla']);

Route::get('/institutes', ['as'=>'dashboard.institutes','uses'=>'DashboardController@getInstitutes']);
Route::get('/institute/list', ['as'=>'dashboard.institute.list','uses'=>'DashboardController@getInstituteList']);
Route::get('/institute/teachers/female', ['as'=>'dashboard.institute.teacher.female','uses'=>'DashboardController@getFemaleTeacherList']);
Route::get('/institute/teachers/male', ['as'=>'dashboard.institute.teacher.male','uses'=>'DashboardController@getMaleTeacherList']);
Route::get('/institute/teachers', ['as'=>'dashboard.institute.teachers','uses'=>'DashboardController@getAllTeacherList']);
Route::get('/institute/teachers/late', ['as'=>'dashboard.institute.teachers.late','uses'=>'DashboardController@getAllTeacherLateList']);
Route::get('/institute/teachers/early', ['as'=>'dashboard.institute.teachers.early','uses'=>'DashboardController@getAllTeacherEarlyLeaveList']);

Route::get('/institutes/create', ['as'=>'dashboard.institutes.create','uses'=>'DashboardController@createInstitute']);
Route::post('/institutes/store', ['as'=>'dashboard.institutes.store','uses'=>'DashboardController@storeInstitute']);
Route::get('/institutes/{id}/edit', ['as'=>'dashboard.institutes.edit','uses'=>'DashboardController@editInstitute']);
Route::put('/institutes/{id}/update', ['as'=>'dashboard.institutes.update','uses'=>'DashboardController@updateInstitute']);
Route::delete('/institutes/{id}/delete', ['as'=>'dashboard.institutes.delete','uses'=>'DashboardController@deleteInstitute']);
Route::get('/institute/{device_id}', ['as'=>'dashboard.institute.single','uses'=>'DashboardController@getSingleInstitute']);
Route::get('/institute/user/create/{device_id}', ['as'=>'dashboard.institute.user.create','uses'=>'DashboardController@createInstituteUser']);
Route::post('/institute/user/store', ['as'=>'dashboard.institute.user.store','uses'=>'DashboardController@storeInstituteUser']);

Route::get('/at-a-glance', ['as'=>'dashboard.ataglance','uses'=>'DashboardController@getAtaGlance']);

Route::get('/personal/profile', ['as'=>'dashboard.personal.profile','uses'=>'DashboardController@getPersonalProfile']);
Route::put('/personal/profile/{id}/update', ['as'=>'dashboard.profile.update','uses'=>'DashboardController@updatePersonalProfile']);



// Temporary
Route::get('/delete/temporary', ['as'=>'index.deletetemporary','uses'=>'IndexController@deleteUpazillasInstitutes']);




// reports
Route::post('/report/institute/pdf/daily/{device_id}', ['as'=>'report.institute.daily','uses'=>'ReportController@getInstituteDailyCombinedReport']);
Route::get('/report/institute/pdf/monthly/{device_id}', ['as'=>'report.institute.monthly','uses'=>'ReportController@getInstituteMonthlyReport']);
Route::post('/report/institute/pdf/query/{device_id}', ['as'=>'report.institute.query','uses'=>'ReportController@getInstituteQueryReport']);
Route::get('/report/institute/pdf/yearly/{device_id}', ['as'=>'report.institute.yearly','uses'=>'ReportController@getInstituteYearlyReport']);

Route::get('/report/teacher/pdf/monthly/{unique_key}', ['as'=>'report.teacher.monthly','uses'=>'ReportController@getTeacherMonthlyReport']);
Route::get('/report/teacher/pdf/yearly/{unique_key}', ['as'=>'report.teacher.yearly','uses'=>'ReportController@getTeacherYearlyReport']);
Route::post('/report/teacher/pdf/query/{unique_key}', ['as'=>'report.teacher.query','uses'=>'ReportController@getTeacherQueryReport']);

// dashboard routes
// dashboard routes
