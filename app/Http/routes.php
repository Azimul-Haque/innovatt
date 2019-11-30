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






Route::get('/staffs', ['as'=>'dashboard.staffs','uses'=>'DashboardController@getStaffs']);
Route::get('/staffs/create', ['as'=>'dashboard.staffs.create','uses'=>'DashboardController@createStaff']);
Route::post('/staffs/store', ['as'=>'dashboard.staffs.store','uses'=>'DashboardController@storeStaff']);
Route::get('/staffs/{id}/edit', ['as'=>'dashboard.staffs.edit','uses'=>'DashboardController@editStaff']);
Route::put('/staffs/{id}/update', ['as'=>'dashboard.staffs.update','uses'=>'DashboardController@updateStaff']);
Route::get('/staffs/add/group/{id}/{routeto}', ['as'=>'dashboard.staffs.getaddgroup','uses'=>'DashboardController@getAddGroupToStaff']);
Route::post('/staffs/add/group/store', ['as'=>'dashboard.staffs.addgroup','uses'=>'DashboardController@addGroupToStaff']);

Route::get('/groups', ['as'=>'dashboard.groups','uses'=>'DashboardController@getGroups']);
Route::get('/groups/create', ['as'=>'dashboard.groups.create','uses'=>'DashboardController@createGroup']);
Route::post('/groups/store', ['as'=>'dashboard.groups.store','uses'=>'DashboardController@storeGroup']);
Route::get('/groups/{id}/edit', ['as'=>'dashboard.groups.edit','uses'=>'DashboardController@editGroup']);
Route::put('/groups/{id}/update', ['as'=>'dashboard.groups.update','uses'=>'DashboardController@updateGroup']);

Route::get('/loanandsavingnames', ['as'=>'dashboard.loanandsavingnames','uses'=>'DashboardController@getLoanAndNames']);
Route::get('/loannames/create', ['as'=>'dashboard.loannames.create','uses'=>'DashboardController@createLoanName']);
Route::post('/loannames/store', ['as'=>'dashboard.loannames.store','uses'=>'DashboardController@storeLoanName']);
Route::get('/loannames/{id}/edit', ['as'=>'dashboard.loannames.edit','uses'=>'DashboardController@editLoanName']);
Route::put('/loannames/{id}/update', ['as'=>'dashboard.loannames.update','uses'=>'DashboardController@updateLoanName']);
Route::get('/savingnames/create', ['as'=>'dashboard.savingnames.create','uses'=>'DashboardController@createSavingName']);
Route::post('/savingnames/store', ['as'=>'dashboard.savingnames.store','uses'=>'DashboardController@storeSavingName']);
Route::get('/savingnames/{id}/edit', ['as'=>'dashboard.savingnames.edit','uses'=>'DashboardController@editSavingName']);
Route::put('/savingnames/{id}/update', ['as'=>'dashboard.savingnames.update','uses'=>'DashboardController@updateSavingName']);
Route::get('/schemenames/create', ['as'=>'dashboard.schemenames.create','uses'=>'DashboardController@createSchemeName']);
Route::post('/schemenames/store', ['as'=>'dashboard.schemenames.store','uses'=>'DashboardController@storeSchemeName']);
Route::get('/schemenames/{id}/edit', ['as'=>'dashboard.schemenames.edit','uses'=>'DashboardController@editSchemeName']);
Route::put('/schemenames/{id}/update', ['as'=>'dashboard.schemenames.update','uses'=>'DashboardController@updateSchemeName']);

Route::get('/group/{s_id}/{g_id}/members', ['as'=>'dashboard.members','uses'=>'MemberController@getMembers']);
Route::get('/group/{s_id}/{g_id}/members/passbook', ['as'=>'dashboard.members.passbooklist','uses'=>'MemberController@getMembersPassbook']);
Route::post('/group/members/update/passbook/api', ['as'=>'dashboard.members.store','uses'=>'MemberController@updatePassBook']);
Route::get('/group/{s_id}/{g_id}/members/create', ['as'=>'dashboard.members.create','uses'=>'MemberController@createMember']);
Route::post('/group/{s_id}/{g_id}/members/store', ['as'=>'dashboard.members.store','uses'=>'MemberController@storeMember']);
Route::get('/group/{s_id}/{g_id}/members/{id}/edit', ['as'=>'dashboard.members.edit','uses'=>'MemberController@editMember']);
Route::put('/group/{s_id}/{g_id}/members/{id}/update', ['as'=>'dashboard.members.update','uses'=>'MemberController@updateMember']);
Route::get('/group/{s_id}/{g_id}/transfer', ['as'=>'dashboard.group.gertransferpage','uses'=>'MemberController@getGroupTransfer']);
Route::put('/group/{id}/transfer', ['as'=>'dashboard.group.transfer','uses'=>'MemberController@transferGroup']);

Route::get('/group/{s_id}/{g_id}/{m_id}/member', ['as'=>'dashboard.member.single','uses'=>'MemberController@getSingleMember']);

// saving accounts
Route::get('/group/{s_id}/{g_id}/{m_id}/member/saving/accounts', ['as'=>'dashboard.member.savings','uses'=>'MemberController@getMemberSavings']);
Route::get('/group/{s_id}/{g_id}/{m_id}/member/saving/accounts/create', ['as'=>'dashboard.savings.create','uses'=>'MemberController@createSavingAccount']);
Route::post('/group/{s_id}/{g_id}/{m_id}/member/saving/accounts/store', ['as'=>'dashboard.savings.store','uses'=>'MemberController@storeSavingAccount']);
Route::put('/group/{s_id}/{g_id}/{m_id}/member/saving/accounts/update/{sv_id}', ['as'=>'dashboard.savings.update','uses'=>'MemberController@updateSavingAccount']);
Route::get('/group/{s_id}/{g_id}/{m_id}/member/saving/accounts/single/{sv_id}', ['as'=>'dashboard.savings.single','uses'=>'MemberController@getMemberSavingSingle']);

// loan accounts
Route::get('/group/{s_id}/{g_id}/{m_id}/member/loan/accounts', ['as'=>'dashboard.member.loans','uses'=>'MemberController@getMemberLoans']);
Route::get('/group/{s_id}/{g_id}/{m_id}/member/loan/accounts/create', ['as'=>'dashboard.loans.create','uses'=>'MemberController@createLoanAccount']);
Route::post('/group/{s_id}/{g_id}/{m_id}/member/loan/accounts/store', ['as'=>'dashboard.loans.store','uses'=>'MemberController@storeLoanAccount']);
Route::put('/group/{s_id}/{g_id}/{m_id}/member/loan/accounts/update/{l_id}', ['as'=>'dashboard.loans.update','uses'=>'MemberController@updateLoanAccount']);
Route::get('/group/{s_id}/{g_id}/{m_id}/member/loan/accounts/single/{l_id}', ['as'=>'dashboard.loans.single','uses'=>'MemberController@getMemberLoanSingle']);

Route::get('/group/{s_id}/{g_id}/{m_id}/member/daily/transaction', ['as'=>'dashboard.member.dailytransaction','uses'=>'MemberController@getDailyTransaction']);
Route::get('/group/{s_id}/{g_id}/{m_id}/member/daily/transaction/{loan_type}/{date}', ['as'=>'dashboard.member.dailytransaction.date','uses'=>'MemberController@getDailyTransactionDate']);
Route::post('/daily/transaction/store/api', ['as'=>'dashboard.dailytransactions.postinstallmentapi','uses'=>'MemberController@postDailyInstallmentAPI']);
Route::post('/old/daily/transaction/store/api', ['as'=>'dashboard.olddailytransactions.postinstallmentapi','uses'=>'MemberController@postOldDailyInstallmentAPI']);

Route::post('/daily/transaction/oldsaving/store/api', ['as'=>'dashboard.dailytransactions.oldsaving.postinstallmentapi','uses'=>'MemberController@postDailyInstallmentOldSavingAPI']);
Route::post('/daily/transaction/newsaving/store/api', ['as'=>'dashboard.dailytransactions.oldsaving.postinstallmentapi','uses'=>'MemberController@postDailyInstallmentNewSavingAPI']);

// group transactions
Route::get('/group/{s_id}/{g_id}/transactions', ['as'=>'dashboard.grouptransactions','uses'=>'GroupController@getGroupTransactions']);
Route::get('/group/{s_id}/{g_id}/transactions/{loan_type}/{date}', ['as'=>'dashboard.grouptransactions.date','uses'=>'GroupController@getGroupTransactionsDate']);
Route::post('/group/transaction/store/api', ['as'=>'dashboard.grouptransactions.postinstallmentapi','uses'=>'GroupController@postGroupInstallmentAPI']);

Route::get('/programs/features', ['as'=>'programs.features','uses'=>'DashboardController@getProgramFeatures']);
Route::get('/staff/{id}/features', ['as'=>'staff.features','uses'=>'StaffController@getStaffFeatures']); // id dhukbe ekhane
Route::get('/group/{s_id}/{g_id}/features', ['as'=>'group.features','uses'=>'GroupController@getGroupFeatures']); // id dhukbe ekhane

// old data entry
Route::get('/old/data/entry', ['as'=>'olddata.index','uses'=>'OldDataEntryContrller@getIndex']);
Route::get('/old/data/entry/create', ['as'=>'olddata.create','uses'=>'OldDataEntryContrller@getCreate']);
Route::post('/old/data/entry/store', ['as'=>'olddata.store','uses'=>'OldDataEntryContrller@storeOldMember']);

// reports
Route::get('/report/test', ['as'=>'report.test','uses'=>'ReportController@test']);
Route::get('/report/program/top/sheet/primary', ['as'=>'report.program.topsheetprimary','uses'=>'ReportController@generateProgramTopSheetPrimary']);
Route::get('/report/program/top/sheet/product', ['as'=>'report.program.topsheetproduct','uses'=>'ReportController@generateProgramTopSheetProduct']);
Route::get('/report/program/top/sheet/savings', ['as'=>'report.program.topsheetsavings','uses'=>'ReportController@generateProgramTopSheetsavings']);
Route::get('/report/program/transaction/summary', ['as'=>'report.program.transactionsummary','uses'=>'ReportController@generateTransactionSummary']);
// dashboard routes
// dashboard routes
