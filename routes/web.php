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

Route::get('/', 'HomeController@index')->name('home');

// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::post('login/facebook', 'Auth\FacebookController@login')->name('login.facebook');
Route::get('login/facebook/callback', 'Auth\FacebookController@callback')->name('login.facebook.callback');

// Registration Routes...
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

// Email Verification Routes...
Route::get('email/verify/{token}', 'VerifyEmailController@verifyEmail')->name('email.verify');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');

// Resource Routes...
Route::resource('articles', 'ArticleController');
Route::resource('tickets', 'TicketController');
Route::resource('tickets/{ticket}/posts', 'TicketPostController');
Route::get('tickets/{ticket}/posts/{ticketPost}/attachment', 'TicketPostController@viewAttachment')->name('posts.attachment');
Route::resource('users', 'UserController');

// Agent Routes...
Route::group(['namespace' => 'Agent', 'prefix' => 'agent', 'as' => 'agent.'], function () {
    Route::get('tickets/closed', 'TicketController@indexClosed')->name('tickets.index.closed');
    Route::resource('tickets', 'TicketController');
});

// Agent Routes...
Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::resource('users', 'UserController');
    Route::resource('roles', 'RoleController');
    Route::resource('permissions', 'PermissionController');
});
