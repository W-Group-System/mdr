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

Route::get('/', function() {
    return redirect('/login');
});

Auth::routes(['register' => false]);

Route::group(['middleware' => 'auth'], function() {
    Route::group(['middleware' => 'deactivate'], function() {
        # Dashboard
        Route::get('/dashboard', 'Admin\DashboardController@index')->name('dashboard');
        Route::post('/print_pdf', 'Admin\DashboardController@printPdf');
        
        Route::group(['middleware' => 'adminAccess'], function() {
            # Departments
            Route::get('/departments', 'Admin\DepartmentController@index')->name('departments');
            Route::post('/addDepartments', 'Admin\DepartmentController@addDepartments')->name('addDepartments');
            Route::post('/updateDepartments/{id}', 'Admin\DepartmentController@updateDepartments')->name('updateDepartments');
            Route::post('/deleteDepartments/{id}', 'Admin\DepartmentController@deleteDepartments')->name('deleteDepartments');
    
            # Department KPI
            Route::get('/department-kpi', 'Admin\DepartmentKPIController@index')->name('departmentKpi');
            Route::post('/addDepartmentKpi', 'Admin\DepartmentKPIController@addDepartmentKpi');
            Route::post('/updateDepartmentsKpi/{id}', 'Admin\DepartmentKPIController@updateDepartmentKpi');
            Route::post('/deleteDepartmentKpi/{id}', 'Admin\DepartmentKPIController@deleteDepartmentKpi');
    
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
        });

        # === Department Head ===
        #MDR
        Route::get('/mdr', 'DeptHead\MdrController@mdrView')->name('mdr');
        Route::get('/new-mdr', 'DeptHead\MdrController@index');
        Route::get('/edit_mdr', 'DeptHead\MdrController@edit');

        # Departmental Goals
        Route::post('/uploadAttachments/{id}', 'DeptHead\DepartmentalGoalsController@uploadAttachments');
        Route::post('/deleteKpiAttachments', 'DeptHead\DepartmentalGoalsController@deleteAttachments');
        Route::post('/create', 'DeptHead\DepartmentalGoalsController@create');
        // Route::post('/update_mdr', 'DeptHead\DepartmentalGoalsController@update');

        # Process Development
        Route::post('/addProcessDevelopment', 'DeptHead\ProcessDevelopmentController@add');
        Route::post('/updateProcessDevelopment/{id}', 'DeptHead\ProcessDevelopmentController@update');
        Route::post('/deleteProcessDevelopment/{id}', 'DeptHead\ProcessDevelopmentController@delete');
        Route::post('/deletePdAttachments', 'DeptHead\ProcessDevelopmentController@deletePdAttachments');

        // # Innovations
        Route::post('/addInnovation', 'DeptHead\InnovationController@add');
        Route::post('/deleteInnovation/{id}', 'DeptHead\InnovationController@delete');
        Route::post('/updateInnovation/{id}', 'DeptHead\InnovationController@update');
        Route::post('/deleteAttachments', 'DeptHead\InnovationController@deleteAttachments');

        # Approve MDR
        Route::post('/approveMdr', 'DeptHead\MdrController@approveMdr');
        Route::post('/submitMdr', 'DeptHead\MdrController@submitMdr');

        # Department Penalties
        Route::get('/department_penalties', 'DeptHead\DepartmentPenaltiesController@index')->name('departmentPenalties');

        // # Business Plan
        // Route::post('/addBusinessPlan', 'DeptHead\BusinessPlanController@add');
        // Route::post('/updateBusinessPlan/{id}', 'DeptHead\BusinessPlanController@update');
        // Route::post('/deleteBusinessPlan/{id}', 'DeptHead\BusinessPlanController@delete');

        // # Ongoing Innovation
        // Route::post('/addOngoingInnovation', 'DeptHead\OngoingInnovationController@add');
        // Route::post('/updateOngoingInnovation/{id}', 'DeptHead\OngoingInnovationController@update');
        // Route::post('/deleteOngoingInnovation/{id}', 'DeptHead\OngoingInnovationController@delete');

        # Approver
        
        # List Of MDR
        Route::get('/list_of_mdr', 'Approver\ListOfMdr@index')->name('listOfMdr');
        Route::post('/return_mdr', 'Approver\ListOfMdr@returnMdr');
        Route::post('/addGradeAndRemarks', 'Approver\ListOfMdr@addGradeAndRemarks');
        Route::post('/approver_mdr', 'Approver\ListOfMdr@approveMdr');
        Route::post('/submit_scores', 'Approver\ListOfMdr@submitScores');

        Route::post('/add_innovation_remarks', 'Approver\ListOfMdr@addInnovationRemarks');
        Route::post('/add_pd_remarks', 'Approver\ListOfMdr@addPdRemarks');

        # For Approval MDR
        Route::get('/for_approval', 'Approver\ForApprovalController@index')->name('forApproval');

        # Pending MDR
        Route::get('pending_mdr', 'Approver\PendingMdrController@index')->name('pendingMdr');

        # History of MDR
        Route::get('/history_mdr', 'Approver\HistoryMdrController@index')->name('historyMdr');

        # Human Resources

        # Penalties
        Route::get('/penalties', 'HR\PenaltiesController@index')->name('penalties');
        Route::post('/upload_nte/{id}', 'HR\PenaltiesController@uploadNte');
        Route::post('/delete_nte/{id}', 'HR\PenaltiesController@deleteNte');
    });
});
