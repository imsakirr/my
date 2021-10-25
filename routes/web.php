<?php

use Illuminate\Support\Facades\Route;

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



//Login & Logout
Route::post('check-login','Auth\LoginController@checkLogin')->name('check-login');
Route::get('logout','Auth\LoginController@logout')->name('logout');
Route::get('login','Auth\LoginController@showLoginForm')->name('login');

//Direct Registration
Route::post('registration-store','Auth\RegisterController@store')->name('store-registration');
Route::get('registration', function () {
    return view('registration');
})->name('new-user');

//Forgot password and reset password
Route::post('forgot-password-check','Auth\RegisterController@checkUser')->name('forgot-password-check');
Route::get('forgot-password/{id}','Auth\RegisterController@forgotForm')->name('forgot-password');
Route::post('reset-password','Auth\RegisterController@resetPassword')->name('reset-password');
Route::get('verify-email/{id}','UserController@verifyEmail')->name('verify-email');
Route::get('forgot', function () {
    return view('forgot');
})->name('forgot');

Route::group(['middleware' => ['web', 'auth']], function() {
    
    //Dashboard 
    Route::get('/','MainController@Home')->name('dashboard');
    Route::get('home','MainController@Home')->name('dashboard');
    
    //Users
    Route::get('create-user','UserController@create')->name('create-user');
    Route::post('user-store','UserController@store')->name('user-store');
    Route::get('user-index','UserController@index')->name('user-index');
    Route::get('user-show/{id}','UserController@show')->name('user-show');
    Route::post('user-edit','UserController@edit')->name('user-edit');
    Route::get('user-delete/{id}','UserController@delete')->name('user-delete');
});