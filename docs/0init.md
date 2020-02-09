# 项目目标
	带有互动功能的直播间。
		1.多直播间聊天（直播功能直接引用video播放m3u8）
		2.直播倒计时、回放
		3.直播间og即加入小组（直播间）
			- 小组密码/暗号
		4.微信自动登录，获取头像、昵称等
# todo
	- 微信登录
	- docker容器化部署
		- socket需要暴露端口给前端Echo
	- 集成m3u8 live播放器
	- 头像，昵称，在线人数
	## done
		- 配置队列redis + queue + horizon
		- 多直播间功能

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
		
		## Redis Session 2号数据库
			@see https://learnku.com/laravel/t/2466/laravel-configuration-under-the-redis-so-that-the-cache-session-each-use-a-different-redis-database
			.env
				SESSION_DRIVER=redis
				SESSION_CONNECTION=session
				SESSION_LIFETIME=43200
			vi config/database.php
				'session' => [
		            'host' => env('REDIS_HOST', '127.0.0.1'),
		            'password' => env('REDIS_PASSWORD', null),
		            'port' => env('REDIS_PORT', 6379),
		            'database' => env('REDIS_CACHE_DB', 2),
		        ],
			
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

	### Çonfig redis Queue
		https://tn710617.github.io/zh-tw/laravelDiggingDeeperQueues/
		https://www.kancloud.cn/php-jdxia/laravel5note/490404
		composer require  predis/predis
		composer require  laravel/horizon
		
			// php artisan queue:table //要不要这个呢？不要了！
			php artisan queue:failed-table // failed_jobs table
			php artisan migrate
		@see https://segmentfault.com/a/1190000015097364
		@url https://www.cnblogs.com/love-snow/articles/7778532.html
			本地调试的时候要使用 php artisan queue:listen 不用 php artisan queue:work
			在开发环境我们想测试的时候，可以把 Queue driver 设置成为 sync，这样队列就变成了同步执行，方便调试队列里面的任务。
			队列任务最大运行时长（秒）可以通过 Artisan 命令上的 --timeout 开关来指定：
				php artisan queue:work --timeout=30
					implements ShouldQueue
						public $timeout = 120;
	### Horizon 
		@url https://laravel.com/docs/6.x/horizon
		composer require  laravel/horizon
			vi app/Providers/HorizonServiceProvider.php
		php artisan horizon
		php artisan horizon:pause
		php artisan horizon:continue
		php artisan horizon:status

		'queue' => ['high','default','low'],
		// When the balance option is set to false, the default Laravel behavior will be used, which processes queues in the order they are listed in your configuration.
		'balance' => 'false', 
			// simple evenly平均分配进程
			// auto 哪个队列任务多，多分配进程
				//'minProcesses' => 1, //scale up
				//'maxProcesses' => 10, //scale down to
			// false 按照config/queues.php中的配置顺序分配进程
				'queue' => env('REDIS_QUEUE', 'default'),
		'processes' => 3,

		If you want to broadcast your event using the sync queue instead of the default queue driver, you can implement the ShouldBroadcastNow interface instead of ShouldBroadcast:

		// Allow Broadcasting a notification now instead on queue!
			https://github.com/laravel/framework/pull/16867
			https://medium.com/@panjeh/laravel-config-horizon-queue-balance-processes-priority-in-redis-c36dd4c16859

	
	### Installing Laravel Echo
		npm install --save laravel-echo pusher-js vue-chat-scroll
	### PrivateChannels 与 PresenceChannels的不同
		https://laravel.com/docs/5.8/broadcasting#presence-channels
		https://pusher.com/docs/channels/using_channels/presence-channels
		相同点：while PrivateChannels and PresenceChannels represent private channels that require channel authorization:
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
