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
Route::get('/', function () {
    return redirect('/dashboard');
});

// Edits
Route::get('/company/{company_id}/project/{project_id}/vtram/{vtram_id}/methodology', [
    'middleware' => 'can:edit-company.project.vtram',
    'uses' => 'CompanyVtramController@editContent'
]);
Route::get('/company/{company_id}/template/{template_id}/methodology', [
    'middleware' => 'can:edit-company.template',
    'uses' => 'CompanyTemplateController@editContent'
]);
Route::get('/template/{template_id}/methodology', [
    'middleware' => 'can:edit-template',
    'uses' => 'TemplateController@editContent'
]);
Route::get('/project/{project_id}/vtram/{vtram_id}/methodology', [
    'middleware' => 'can:edit-project.vtram',
    'uses' => 'VtramController@editContent'
]);

// Approvals
Route::get('/company/{company_id}/project/{project_id}/vtram/{vtram_id}/approve', [
    'middleware' => 'can:create-company.project.vtram.approval',
    'uses' => 'CompanyApprovalController@viewApproval'
]);
Route::get('/company/{company_id}/template/{template_id}/approve', [
    'middleware' => 'can:create-company.template.approval',
    'uses' => 'CompanyTemplateApprovalController@viewApproval'
]);
Route::get('/template/{template_id}/approve', [
    'middleware' => 'can:create-template.approval',
    'uses' => 'TemplateApprovalController@viewApproval'
]);
Route::get('/project/{project_id}/vtram/{vtram_id}/approve', [
    'middleware' => 'can:create-project.vtram.approval',
    'uses' => 'ApprovalController@viewApproval'
]);
Route::post('/company/{company_id}/project/{project_id}/vtram/{vtram_id}/approve', [
    'middleware' => 'can:create-company.project.vtram.approval',
    'uses' => 'CompanyApprovalController@store'
]);
Route::post('/company/{company_id}/template/{template_id}/approve', [
    'middleware' => 'can:create-company.template.approval',
    'uses' => 'CompanyTemplateApprovalController@store'
]);
Route::post('/template/{template_id}/approve', [
    'middleware' => 'can:create-template.approval',
    'uses' => 'TemplateApprovalController@store'
]);
Route::post('/project/{project_id}/vtram/{vtram_id}/approve', [
    'middleware' => 'can:create-project.vtram.approval',
    'uses' => 'ApprovalController@store'
]);

// Clone
Route::get('/company/{company_id}/clone', [
    'middleware' => 'can:create-company',
    'uses' => 'CompanyController@clone',
]);

Route::get('user.datatable.json', 'WorksafeUserController@_datatableAll');
Route::get('user.json', 'WorksafeUserController@jsonAll');
Route::group(['prefix' => 'user'], function () {
    Route::get('', 'WorksafeUserController@_index');
    Route::get('create', 'WorksafeUserController@_create');
    Route::post('create', 'WorksafeUserController@store');
    Route::get('{user_id}', 'WorksafeUserController@_view');
    Route::get('{user_id}.json', 'WorksafeUserController@jsonRow');
    Route::get('{user_id}/delete', 'WorksafeUserController@_delete');
    Route::post('{user_id}/edit', 'WorksafeUserController@update');
    Route::get('{user_id}/edit', 'WorksafeUserController@_edit');
    Route::get('{user_id}/permanentlyDelete', 'WorksafeUserController@_permanentlyDelete');
    Route::get('{user_id}/restore', 'WorksafeUserController@_restore');
    Route::get('{user_id}/view', 'WorksafeUserController@_view');
});
Route::group(['prefix' => 'your_details'], function () {
    Route::get('', 'WorksafeYourDetailsController@_view');
    Route::get('edit', 'WorksafeYourDetailsController@_edit');
    Route::post('edit', 'WorksafeYourDetailsController@update');
});

// VTRAM Actions
Route::get('/company/{company_id}/project/{project_id}/vtram/{vtram_id}/submit', [
    'middleware' => 'can:edit-company.project.vtram',
    'uses' => 'CompanyVtramController@submitForApproval'
]);
Route::get('/company/{company_id}/project/{project_id}/vtram/{vtram_id}/previous/{previous_id}/view_a3', [
    'middleware' => 'can:view-company.project.vtram.previous',
    'uses' => 'CompanyPreviousVtramController@viewA3'
]);
Route::get('/company/{company_id}/project/{project_id}/vtram/{vtram_id}/view_a3', [
    'middleware' => 'can:view-company.project.vtram',
    'uses' => 'CompanyVtramController@viewA3'
]);
Route::get('/project/{project_id}/vtram/{vtram_id}/submit', [
    'middleware' => 'can:edit-project.vtram',
    'uses' => 'VtramController@submitForApproval'
]);
Route::get('/project/{project_id}/vtram/{vtram_id}/view_a3', [
    'middleware' => 'can:view-project.vtram',
    'uses' => 'VtramController@viewA3'
]);
Route::get('/project/{project_id}/vtram/{vtram_id}/previous/{previous_id}/view_a3', [
    'middleware' => 'can:view-project.vtram.previous',
    'uses' => 'PreviousVtramController@viewA3'
]);

// Template Actions
Route::get('/company/{company_id}/template/{template_id}/view_a3', [
    'middleware' => 'can:view-company.template',
    'uses' => 'CompanyTemplateController@viewA3'
]);
Route::get('/company/{company_id}/template/{template_id}/submit', [
    'middleware' => 'can:edit-company.template',
    'uses' => 'CompanyTemplateController@submitForApproval'
]);
Route::get('/company/{company_id}/template/{template_id}/previous/{previous_id}/view_a3', [
    'middleware' => 'can:view-company.template.previous',
    'uses' => 'PreviousCompanyTemplateController@viewA3'
]);
Route::get('/template/{template_id}/previous/{previous_id}/view_a3', [
    'middleware' => 'can:view-template.previous',
    'uses' => 'PreviousTemplateController@viewA3'
]);
Route::get('/template/{template_id}/submit', [
    'middleware' => 'can:edit-template',
    'uses' => 'TemplateController@submitForApproval'
]);
Route::get('/template/{template_id}/view_a3', [
    'middleware' => 'can:view-template',
    'uses' => 'TemplateController@viewA3'
]);

// Hazards
Route::post('/hazard/{id}/delete_hazard', [
    'middleware' => 'can:delete-hazard',
    'uses' => 'HazardController@delete'
]);

Route::post('/hazard/{id}/move_up', [
    'middleware' => 'can:edit-hazard',
    'uses' => 'HazardController@moveUp'
]);

Route::post('/hazard/{id}/move_down', [
    'middleware' => 'can:edit-hazard',
    'uses' => 'HazardController@moveDown'
]);

// create
Route::post('/company/{company_id}/project/{project_id}/vtram/{vtram_id}/hazard/create', [
    'middleware' => 'can:create-hazard',
    'uses' => 'HazardController@store'
]);

Route::post('/project/{project_id}/vtram/{vtram_id}/hazard/create', [
    'middleware' => 'can:create-hazard',
    'uses' => 'HazardController@store'
]);

Route::post('/company/{company_id}/template/{template_id}/hazard/create', [
    'middleware' => 'can:create-hazard',
    'uses' => 'HazardController@store'
]);

Route::post('/template/{template_id}/hazard/create', [
    'middleware' => 'can:create-hazard',
    'uses' => 'HazardController@store'
]);

// edit
Route::post('/company/{company_id}/project/{project_id}/vtram/{vtram_id}/hazard/{hazard_id}/edit', [
    'middleware' => 'can:edit-hazard',
    'uses' => 'HazardController@update'
]);

Route::post('/project/{project_id}/vtram/{vtram_id}/hazard/{hazard_id}/edit', [
    'middleware' => 'can:edit-hazard',
    'uses' => 'HazardController@update'
]);

Route::post('/company/{company_id}/template/{template_id}/hazard/{hazard_id}/edit', [
    'middleware' => 'can:edit-hazard',
    'uses' => 'HazardController@update'
]);

Route::post('/template/{template_id}/hazard/{hazard_id}/edit', [
    'middleware' => 'can:edit-hazard',
    'uses' => 'HazardController@update'
]);

// update methodologies page - routes
Route::post('/company/{company_id}/project/{project_id}/vtram/{vtram_id}/edit_extra', [
    'middleware' => 'can:edit-company.project.vtram',
    'uses' => 'CompanyVtramController@updateFromMethodology'
]);

Route::post('/project/{project_id}/vtram/{vtram_id}/edit_extra', [
    'middleware' => 'can:edit-project.vtram',
    'uses' => 'VtramController@updateFromMethodology'
]);

Route::post('/company/{company_id}/template/{template_id}/edit_extra', [
    'middleware' => 'can:edit-company.template',
    'uses' => 'CompanyTemplateController@updateFromMethodology'
]);

Route::post('/template/{template_id}/edit_extra', [
    'middleware' => 'can:edit-template',
    'uses' => 'TemplateController@updateFromMethodology'
]);

// comments

Route::get('/company/{company_id}/project/{project_id}/vtram/{vtram_id}/comment', [
    'middleware' => 'can:view-company.project.vtram',
    'uses' => 'CompanyVtramController@commentsList'
]);

Route::get('/project/{project_id}/vtram/{vtram_id}/comment', [
    'middleware' => 'can:view-project.vtram',
    'uses' => 'VtramController@commentsList'
]);

Route::get('/company/{company_id}/template/{template_id}/comment', [
    'middleware' => 'can:view-company.template',
    'uses' => 'CompanyTemplateController@commentsList'
]);

Route::get('/template/{template_id}/comment', [
    'middleware' => 'can:view-template',
    'uses' => 'TemplateController@commentsList'
]);


// Methodologies

// create
Route::post('/company/{company_id}/project/{project_id}/vtram/{vtram_id}/methodology/create', [
    'middleware' => 'can:create-methodology',
    'uses' => 'MethodologyController@store'
]);

Route::post('/project/{project_id}/vtram/{vtram_id}/methodology/create', [
    'middleware' => 'can:create-methodology',
    'uses' => 'MethodologyController@store'
]);

Route::post('/company/{company_id}/template/{template_id}/methodology/create', [
    'middleware' => 'can:create-methodology',
    'uses' => 'MethodologyController@store'
]);

Route::post('/template/{template_id}/methodology/create', [
    'middleware' => 'can:create-methodology',
    'uses' => 'MethodologyController@store'
]);

// edit
Route::post('/company/{company_id}/project/{project_id}/vtram/{vtram_id}/methodology/{methodology_id}/edit', [
    'middleware' => 'can:edit-methodology',
    'uses' => 'MethodologyController@update'
]);

Route::post('/project/{project_id}/vtram/{vtram_id}/methodology/{methodology_id}/edit', [
    'middleware' => 'can:edit-methodology',
    'uses' => 'MethodologyController@update'
]);

Route::post('/company/{company_id}/template/{template_id}/methodology/{methodology_id}/edit', [
    'middleware' => 'can:edit-methodology',
    'uses' => 'MethodologyController@update'
]);

Route::post('/template/{template_id}/methodology/{methodology_id}/edit', [
    'middleware' => 'can:edit-methodology',
    'uses' => 'MethodologyController@update'
]);

Route::post('/methodology/{id}/delete_methodology', [
    'middleware' => 'can:delete-methodology',
    'uses' => 'MethodologyController@delete'
]);

Route::post('/methodology/{id}/move_up', [
    'middleware' => 'can:edit-methodology',
    'uses' => 'MethodologyController@moveUp'
]);

Route::post('/methodology/{id}/move_down', [
    'middleware' => 'can:edit-methodology',
    'uses' => 'MethodologyController@moveDown'
]);

