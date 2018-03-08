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

Route::post('login/google', 'Auth\GoogleController@login')->name('login.google');
Route::get('login/google/callback', 'Auth\GoogleController@callback')->name('login.google.callback');

Route::get('login/two-factor', 'Auth\Google2FAController@showForm')->name('login.2fa');
Route::post('login/two-factor', 'Auth\Google2FAController@login');

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

// Search Routes...
Route::get('search', 'SearchController@search')->name('search');

// Resource Routes...
Route::resource('announcements', 'AnnouncementController');
Route::resource('articles', 'ArticleController');
Route::resource('articles.comments', 'ArticleCommentController');
Route::resource('tickets', 'TicketController');
Route::resource('tickets.posts', 'TicketPostController');
Route::get('tickets/{ticket}/posts/{post}/attachment', 'TicketPostController@viewAttachment')->name('tickets.posts.attachment');

Route::get('profile', 'ProfileController@show')->name('profile.show');
Route::put('profile', 'ProfileController@update')->name('profile.update');
Route::get('profile/notifications', 'UserNotificationsController@show')->name('profile.notifications.show');
Route::post('profile/notifications', 'UserNotificationsController@store')->name('profile.notifications.store');
Route::put('profile/notifications', 'UserNotificationsController@update')->name('profile.notifications.update');

Route::get('settings/two-factor', 'SettingsController@show2FAForm')->name('settings.2fa');
Route::post('settings/two-factor', 'SettingsController@register2FA');

// Agent Routes...
Route::group(['namespace' => 'Agent', 'prefix' => 'agent', 'as' => 'agent.'], function () {
    Route::get('tickets/closed', 'TicketController@indexClosed')->name('tickets.index.closed');
    Route::resource('tickets', 'TicketController');
});

// Admin Routes...
Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::resource('users', 'UserController');
    Route::resource('roles', 'RoleController');
    Route::resource('permissions', 'PermissionController');
});
