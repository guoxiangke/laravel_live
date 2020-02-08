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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
// broadcast(new App\Events\WebsocketDemoEvent('welcome data!'));
Route::get('/chats', 'ChatsController@index');

Route::resources(['live' => 'LiveController']);
Route::get('/messages', 'MessageController@fetchMessages');
Route::post('/messages', 'MessageController@sendMessage');

