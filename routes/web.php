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
Route::get('/company/{company_id}/project/{project_id}/vtram/{vtram_id}/methodology', 'CompanyVtramController@editContent');
Route::get('/company/{company_id}/template/{template_id}/methodology', 'CompanyTemplateController@editContent');
Route::get('/template/{template_id}/methodology', 'TemplateController@editContent');
Route::get('/project/{project_id}/vtram/{vtram_id}/methodology', 'VtramController@editContent');

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
