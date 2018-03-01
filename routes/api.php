<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/', 'HomeController@index')->name('home');

Route::resource('announcements', 'AnnouncementController');
Route::resource('articles', 'ArticleController');
Route::resource('articles.comments', 'ArticleCommentController');
Route::resource('permissions', 'PermissionController');
Route::resource('roles', 'RoleController');
Route::resource('tickets', 'TicketController');
Route::resource('tickets.posts', 'TicketPostController');
Route::resource('users', 'UserController');
