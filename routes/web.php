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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes(['register' => false]);

Route::group(['middleware' => 'auth'], function() {
    Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

    # Dashboard
    Route::get('/dashboard', 'Admin\DashboardController@index')->name('dashboard');

    # Departments
    Route::get('/departments', 'Admin\DepartmentController@index')->name('departments');
    Route::post('/addDepartments', 'Admin\DepartmentController@addDepartments')->name('addDepartments');
    Route::post('/updateDepartments/{id}', 'Admin\DepartmentController@updateDepartments')->name('updateDepartments');
    Route::post('/deleteDepartments/{id}', 'Admin\DepartmentController@deleteDepartments')->name('deleteDepartments');

    # Department KPI
    Route::get('/department-kpi', 'Admin\DepartmentKPIController@index')->name('departmentKpi');
    Route::post('/addDepartmentKpi', 'Admin\DepartmentKPIController@addDepartmentKpi');
    Route::post('/updateDepartmentsKpi/{id}', 'Admin\DepartmentKPIController@updateDepartmentKpi');

    # Department Group
    Route::get('/department-groups', 'Admin\DepartmentGroupController@index')->name('departmentGroup');
    Route::post('/addDepartmentGroups', 'Admin\DepartmentGroupController@addDepartmentGroups');
    Route::post('/updateDepartmentGroups/{id}', 'Admin\DepartmentGroupController@updateDepartmentGroups');
    Route::post('/deleteDepartmentGroups/{id}', 'Admin\DepartmentGroupController@deleteDepartmentGroups');

    # User Accounts
    Route::get('/user-accounts', 'Admin\UserController@index')->name('userAccounts');
    Route::post('/addUserAccounts', 'Admin\UserController@addUserAccounts')->name('addUserAccounts');
    Route::post('/updateUserAccounts/{id}', 'Admin\UserController@updateUserAccounts');
    Route::post('/changePassword/{id}', 'Admin\UserController@changePassword');
    Route::post('/changeAccountStatus', 'Admin\UserController@changeAccountStatus')->name('changeAccountStatus');
    Route::post('/changePassword/{id}', 'Admin\UserController@changePassword')->name('changePassword');

    # Manage Approver
    Route::get('/manage-approver', 'Admin\ApproverController@index')->name('manageApprover');
    Route::post('/updateApprover/{id}', 'Admin\ApproverController@updateApprover')->name('updateApprover');

    # === Department Head ===
    #MDR
    Route::get('/mdr', 'DeptHead\MdrController@index')->name('mdr');

    # Departmental Goals
    Route::post('/addActual/{id}', 'DeptHead\DepartmentalGoalsController@addActual');
    Route::post('/addRemarks/{id}', 'DeptHead\DepartmentalGoalsController@addRemarks');
    Route::post('/uploadAttachments/{id}', 'DeptHead\DepartmentalGoalsController@uploadAttachments');

    # Innovations
    Route::post('/addInnovation', 'DeptHead\InnovationController@add');
    Route::post('/deleteInnovation/{id}', 'DeptHead\InnovationController@delete');
    Route::post('/updateInnovation/{id}', 'DeptHead\InnovationController@update');

    # Business Plan
    Route::post('/addBusinessPlan', 'DeptHead\BusinessPlanController@add');
    Route::post('/updateBusinessPlan/{id}', 'DeptHead\BusinessPlanController@update');
    Route::post('/deleteBusinessPlan/{id}', 'DeptHead\BusinessPlanController@delete');
});
