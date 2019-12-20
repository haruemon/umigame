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

Route::get('/', 'UmigameController@index');
Route::post('/umigame', 'UmigameController@answer')->name('answer');
Route::get('/result', 'UmigameController@result')->name('result');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
