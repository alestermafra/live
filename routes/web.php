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

Route::get('/messages', 'MessageController@index')->name('messages.index');
Route::post('/messages', 'MessageController@store')->name('messages.store');
Route::delete('/messages/{message}', 'MessageController@delete')->name('messages.delete');
Route::view('/', 'live');