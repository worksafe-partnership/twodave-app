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

// Temporary Delete Action for Hazards until I figure out the correct way:
Route::post('/hazard/{id}/deleteHazard', [
    // 'middleware' => 'can:permanentlyDelete-hazard', // permissions were off when I picked this up, put this back in when they are on.
    'uses' => 'HazardController@delete'
]);
