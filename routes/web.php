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
Route::post('live/message/{live}', 'MessageController@storeLive');
Route::resources(['messages' => 'MessageController']);
// Route::get('/messages/{id}', 'MessageController@fetchMessages');
// Route::post('/messages', 'MessageController@sendMessage');

//微信社交认证登陆路由
$wechatVerifyCode = env('WEIXIN_VERIFY_CODE', 'Need config WEIXIN_VERIFY_CODE to Verify!');

Route::get('/MP_verify_'.$wechatVerifyCode.'.txt', function () use($wechatVerifyCode) {
    return $wechatVerifyCode;
});
Route::resources(['socials' => 'SocialController']);
Route::get('login/wechat/callback', 'Auth\LoginSocialController@handleWechatProviderCallback')->name('login.weixin.callback');
//login.weixin
// 自动登录跳转 https://laracasts.com/discuss/channels/laravel/custom-login-page-redirection-from-middleware
Route::get('login/wechat', 'Auth\LoginSocialController@redirectToWechatProvider')//->name('login.weixin');
	->name('login');
Route::post('login/wechat', 'Auth\LoginController@login');
Route::get('login', 'Auth\LoginController@showLoginForm');
