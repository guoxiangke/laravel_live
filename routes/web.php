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


Route::resources(['live' => 'LiveController']);
Route::resources(['messages' => 'MessageController']);
// Route::get('/messages/{id}', 'MessageController@fetchMessages');
// Route::post('/messages', 'MessageController@sendMessage');

//微信社交认证登陆路由
$wechatVerifyCode = env('WEIXIN_VERIFY_CODE', 'Need config WEIXIN_VERIFY_CODE to Verify!');
Route::get('/MP_verify_'.$wechatVerifyCode.'.txt', function () {
    return $wechatVerifyCode;
});
Route::resources(['socials' => 'SocialController']);
Route::get('login/wechat/callback', 'Auth\LoginSocialController@handleWechatProviderCallback')->name('login.weixin.callback');
Route::get('login/wechat', 'Auth\LoginSocialController@redirectToWechatProvider')->name('login.weixin');

