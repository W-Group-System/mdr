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

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes(['register' => false]);
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
Route::group(['middleware' => 'auth'], function () {
    Route::group(['middleware' => 'deactivate'], function () {
        # Dashboard
        Route::get('/dashboard', 'Admin\DashboardController@index')->name('dashboard')->middleware('humanResource');
        Route::post('/print_pdf', 'Admin\DashboardController@printPdf');

        # Departments
        Route::get('/departments', 'Admin\DepartmentController@index')->name('settings');
        Route::post('/addDepartments', 'Admin\DepartmentController@addDepartments');
        Route::post('/updateDepartments/{id}', 'Admin\DepartmentController@updateDepartments');
        Route::post('/deactivate_department/{id}', 'Admin\DepartmentController@deactivate');
        Route::post('/activate_department/{id}', 'Admin\DepartmentController@activate');

        # MDR Setups
        Route::get('/department_kpis', 'Admin\DepartmentKpiController@index')->name('mdr');
        Route::post('/addDepartmentKpi', 'Admin\DepartmentKpiController@addDepartmentKpi');
        Route::post('/updateDepartmentsKpi/{id}', 'Admin\DepartmentKpiController@updateDepartmentKpi');
        Route::post('/deactivate_mdr_setup/{id}', 'Admin\DepartmentKpiController@deactivate');
        Route::post('/activate_mdr_setup/{id}', 'Admin\DepartmentKpiController@activate');

        # Companies
        Route::get('/companies', 'Admin\CompanyController@index')->name('settings');
        Route::post('/add_company', 'Admin\CompanyController@store');
        Route::post('/update_company/{id}', 'Admin\CompanyController@update');
        Route::post('/deactivate_company/{id}', 'Admin\CompanyController@deactivate');
        Route::post('/activate_company/{id}', 'Admin\CompanyController@activate');

        # MDR Groups
        Route::get('/mdr_group', 'Admin\MdrGroupController@index')->name('mdr');
        Route::post('/addDepartmentGroups', 'Admin\MdrGroupController@addDepartmentGroups');
        Route::post('/updateDepartmentGroups/{id}', 'Admin\MdrGroupController@updateDepartmentGroups');
        Route::post('/deactivate_mdr_group/{id}', 'Admin\MdrGroupController@deactivate');
        Route::post('/activate_mdr_group/{id}', 'Admin\MdrGroupController@activate');

        # User Accounts
        Route::get('/user-accounts', 'Admin\UserController@index')->name('settings');
        Route::post('/addUserAccounts', 'Admin\UserController@addUserAccounts');
        Route::post('/updateUserAccounts/{id}', 'Admin\UserController@updateUserAccounts');
        Route::post('/changeAccountStatus', 'Admin\UserController@changeAccountStatus')->name('changeAccountStatus');
        Route::post('/changePassword/{id}', 'Admin\UserController@changePassword')->name('changePassword');
        Route::get('user_access_module/{id}','Admin\UserController@userAccessModule');
        Route::post('store_access_module','Admin\UserController@storeAccessModule');

        # === Department Head ===
        #MDR
        Route::get('/mdr', 'DeptHead\MdrController@mdrView')->name('mdr');
        Route::get('/new-mdr', 'DeptHead\MdrController@index');
        Route::get('/edit_mdr', 'DeptHead\MdrController@edit');

        # Departmental Goals
        // Route::post('/uploadAttachments/{id}', 'DeptHead\DepartmentalGoalsController@uploadAttachments');
        Route::post('/deleteKpiAttachments', 'DeptHead\DepartmentalGoalsController@deleteAttachments');
        Route::post('/create', 'DeptHead\DepartmentalGoalsController@create');
        Route::post('/update_kpi', 'DeptHead\DepartmentalGoalsController@update');
        Route::post('store_comments','DeptHead\DepartmentalGoalsController@comments');

        # Process Development
        Route::post('/addProcessDevelopment', 'DeptHead\ProcessDevelopmentController@add');
        Route::post('/updateProcessDevelopment/{id}', 'DeptHead\ProcessDevelopmentController@update');
        Route::post('/deleteProcessDevelopment/{id}', 'DeptHead\ProcessDevelopmentController@delete');
        Route::post('/deletePdAttachments', 'DeptHead\ProcessDevelopmentController@deletePdAttachments');

        // # Innovations
        Route::post('/addInnovation', 'DeptHead\InnovationController@add');
        Route::post('/deleteInnovation/{id}', 'DeptHead\InnovationController@delete');
        Route::post('/updateInnovation/{id}', 'DeptHead\InnovationController@update');
        Route::post('/deleteAttachments/{id}', 'DeptHead\InnovationController@deleteAttachments');

        # DepartmentApprovers MDR
        Route::post('/approveMdr', 'DeptHead\MdrController@approveMdr');
        Route::post('/submitMdr', 'DeptHead\MdrController@submitMdr');

        # Department Penalties
        // Route::get('/department_penalties', 'DeptHead\DepartmentPenaltiesController@index')->name('departmentPenalties');
        // Route::get('/department_nod', 'DeptHead\DepartmentNodController@index')->name('departmentPenalties');

        # Approver

        # List Of MDR
        Route::get('/list_of_mdr/{id}', 'Approver\ListOfMdr@index')->name('listOfMdr');
        Route::post('/return_mdr', 'Approver\ListOfMdr@returnMdr');
        Route::post('/addGradeAndRemarks', 'Approver\ListOfMdr@addGradeAndRemarks');
        Route::post('/approver_mdr/{id}', 'Approver\ListOfMdr@approveMdr');
        Route::post('/submit_scores/{id}', 'Approver\ListOfMdr@submitScores');

        // Route::post('/add_innovation_remarks', 'Approver\ListOfMdr@addInnovationRemarks');
        Route::post('/add_pd_remarks/{id}', 'Approver\ListOfMdr@addPdRemarks');
        Route::post('/accept_mdr/{id}', 'Approver\ListOfMdr@acceptMdr');

        # For Acceptance MDR
        Route::get('/for_acceptance', 'Approver\ForApprovalController@forAcceptance')->name('forAcceptance');
        Route::post('/approveTime/{id}', 'Approver\ForApprovalController@approveTimeliness');
        Route::post('/disapproveTime/{id}', 'Approver\ForApprovalController@disapproveTimeliness');
        
        # For Acceptance MDR
        Route::get('/timeliness_approval', 'Approver\ForApprovalController@timelinessApproval')->name('timelinessApproval');
        
        # For Approval MDR
        Route::get('/for_approval', 'Approver\ForApprovalController@index')->name('forApproval');

        # Pending MDR
        Route::get('mdr_list', 'Approver\PendingMdrController@index')->name('pendingMdr');

        # History of MDR
        Route::get('/mdr_reports', 'MdrReportController@index')->name('historyMdr');
        Route::post('store_remarks','MdrReportController@store');

        # List of Penalties
        Route::get('/list_of_penalties', 'Approver\ListOfPenaltiesController@index')->name('listOfPenalties');

        # Human Resources

        # Penalties
        // Route::get('/notice_of_explanation', 'HR\ForNteController@index')->name('ntePenalties');
        // Route::post('/upload_nte/{id}', 'HR\ForNteController@uploadNte');
        // Route::post('/nte_status/{id}', 'HR\ForNteController@nteStatus');

        // Route::get('/notice_of_disciplinary', 'HR\ForNodController@index')->name('ntePenalties');
        // Route::post('/upload_nod/{id}', 'HR\ForNodController@uploadNod');
        // Route::post('/nod_status/{id}', 'HR\ForNodController@nodStatus');

        // Route::get('/performance_improvement_plan', 'HR\ForPipController@index')->name('ntePenalties');
        // Route::post('/upload_pip/{id}', 'HR\ForPipController@uploadPip');
        // Route::post('/pip_status/{id}', 'HR\ForPipController@pipStatus');

        # Department Approvers
        Route::get('department-approvers', 'DepartmentApproverController@index')->name('settings');
        Route::post('store-department-approvers', 'DepartmentApproverController@store');
        Route::post('update-department-approvers/{id}', 'DepartmentApproverController@update');
        Route::post('activate-department-approvers/{id}', 'DepartmentApproverController@activate');
        Route::post('deactivate-department-approvers/{id}', 'DepartmentApproverController@deactivate');
    });
});
