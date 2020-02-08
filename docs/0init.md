# 项目目标
	带有互动功能的直播间。
		1.多直播间聊天（直播功能直接引用video播放m3u8）
		2.直播倒计时、回放
		3.直播间og即加入小组（直播间）
			- 小组密码/暗号
		4.微信自动登录，获取头像、昵称等
# 安装laravel

	```laravel new laravel6
	// composer create-project --prefer-dist laravel/laravel ```

# 主题设置
	The Bootstrap and Vue scaffolding provided by Laravel is located in the laravel/ui Composer package, which may be installed using Composer:
	```composer require laravel/ui --dev```
	Once the laravel/ui package has been installed, you may install the frontend scaffolding using the ui Artisan command:

	```php artisan ui vue --auth```
	```npm install && npm run watch```
# 全局设置
	## SoftDelete for every Model!!!
		$table->softDeletes();
		use Illuminate\Database\Eloquent\SoftDeletes;

		use SoftDeletes;

	## Schemaless for every table!!!

		$table->schemalessAttributes('extra_attributes');
		use App\Traits\HasSchemalessAttributes;

	    use HasSchemalessAttributes;
	    const EXTRA_ATTRIBUTES = ['to','do'];
	    public $casts = [
	        'extra_attributes' => 'array',
	    ];

	## LogsActivity
		use Spatie\Activitylog\Traits\LogsActivity;

		use LogsActivity;
	    protected static $logAttributes = ['*'];
	    protected static $logAttributesToIgnore = [ 'none'];
	    protected static $logOnlyDirty = true;
		composer require spatie/laravel-query-builder

	## 

# 安装必要插件
	## 并行下载插件
		```composer global require "hirak/prestissimo"```

		composer require  predis/predis 
		composer require  laravel/horizon
		composer require  laravel/socialite
		composer require  mtvs/eloquent-hashids
		composer require  simshaun/recurr
			https://github.com/simshaun/recurr
	## 开发相关
		### telescope
			Telescope provides insight into the requests coming into your application, exceptions, log entries, database queries, queued jobs, mail, notifications, cache operations, scheduled tasks, variable dumps and more. 
				composer require laravel/telescope --dev
				php artisan telescope:install
				php artisan migrate
				// config/telescope.php
				// .env => TELESCOPE_ENABLED=true
			When updating Telescope, you should re-publish Telescope's assets:
				php artisan telescope:publish


	## 必要插件
		### ALL

		### 字段补充 laravel-schemaless-attributes
			https://github.com/spatie/laravel-schemaless-attributes
			```composer require spatie/laravel-schemaless-attributes```
			mkdir app/Traits
			touch app/Traits/HasSchemalessAttributes.php
		### 上传文件管理 laravel-medialibrary
			composer require spatie/laravel-medialibrary
			php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="migrations"
		### 
			composer require spatie/laravel-permission
		### 
			composer require spatie/laravel-activitylog

				
		### 云存储 
			composer require "overtrue/laravel-filesystem-qiniu"

		### Redis broadcaster
			.env
				BROADCAST_DRIVER=log => BROADCAST_DRIVER=redis
			config/broadcasting.php
			composer require predis/predis

## 直播聊天室
	### Introduce
		@see https://github.com/qirolab/Laravel-WebSockets-Chat-Example
		Introducing laravel-websockets
			It completely replaces the need for a service like Pusher or a JavaScript-based laravel-echo-server.
		
		you only need to uncomment this provider in the providers array of your config/app.php configuration file.
		// App\Providers\BroadcastServiceProvider::class,
		// add <meta name="csrf-token" content="{{ csrf_token() }}"> to head of HTML
		
		Queue Prerequisites
			Before broadcasting events, you will also need to configure and run a queue listener. All event broadcasting is done via queued jobs so that the response time of your application is not seriously affected.
	
	### Install
		composer require beyondcode/laravel-websockets
			php artisan vendor:publish --provider="BeyondCode\LaravelWebSockets\WebSocketsServiceProvider" --tag="migrations"
			php artisan migrate
			php artisan vendor:publish --provider="BeyondCode\LaravelWebSockets\WebSocketsServiceProvider" --tag="config"
				'path' => 'admin/websockets',
		Dashboard
			http://127.0.0.1:8000/admin/websockets

	### Çonfig redis Queue / horizon work
		composer require  predis/predis
		composer require  laravel/horizon

	### Installing Laravel Echo
		npm install --save laravel-echo pusher-js vue-chat-scroll
## xx 




## users / User
	### Avatar
		$table->string('avatar')->nullable();
	### hasMany Profile 一个用户可以拥有多份用户资料
		return $this->hasMany(Profile::class);
## profiles / Profile
	php artisan make:model Models\\Profile --all



## mtvs/eloquent-hashids

## user login with mobile 

## user social login

## user role permission

## weixin/social login & weixin user profile

	
composer require inertiajs/inertia-laravel tightenco/ziggy reinink/remember-query-strings
npm install @inertiajs/inertia @inertiajs/inertia-vue @babel/plugin-syntax-dynamic-import  portal-vue --save-dev

## 经验/bug
	### laravel Broadcaster config中的pusher和Pusher Channels的区别！ https://pusher.com/channels
	### tinker中生成测试数据
		factory(\App\Models\Message::class, 1)->create() # tinker必须要第一个\
	### 直播 model & Route::resource('lives', 'LiveController');  bug！
		lives的单数 ==》life了！
		live 改为 chats或
		Route::resource('lives', 'LiveController'); =》改为
		Route::resource('live', 'LiveController');
