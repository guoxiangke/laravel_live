# 项目目标
	带有互动功能的直播间。
		1.多直播间聊天（直播功能直接引用video播放m3u8）
		2.直播倒计时、回放
		3.直播间og即加入小组（直播间）
			- 小组密码/暗号
		4.微信自动登录，获取头像、昵称等
# todo
	Album/Course
		groupable!
	聊天
		语音？
		@mention？
		踢人！!!
	创建群组聊天
		1.手机号、姓名、profile 认证！
		2.创建1个/10元/月，100/年
		live from => to
		rrule =》 active_at //直播时段、开启聊天，其他时间，禁止聊天！
	Live 群组
		只有会员能加入Live小组！
		会员申请加入，approve！
			need approve?!
			审核会员！
				是否需要填入会员信息，字段？
			群组成员！
			og_member
			OgMembership
				isGroup
				isGroupContent
			一个文章属于 某个组
			一个用户是某个组的memeber
			memberable	groupable
			post_id/user_id  group_id/live_id,
		申请加入
		price
	user
		login with telephone!
	role
	permisson
	CURD of Live
	CURD of Rrule

	model json filter
	一对一聊天？
		/current_user/chat/to_uid
	群组聊天without of live.
	## improve
		laravel-websockets => socket.io
	## done
		- bluma instead of bootstrap
		- 集成m3u8 live播放器
		- 头像，昵称，在线人数
		- docker容器化部署
			- socket需要暴露端口给前端Echo
		- 配置队列redis + queue + horizon
		- 多直播间功能
		- 微信登录	

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

## 微信登录
	composer require socialiteproviders/weixin
	// 注意网站实现微信登录需要的依赖包为socialiteproviders/weixin-web，如果是手机端App那么可以用socialiteproviders/weixin。
	vi Providers/RouteServiceProvide.php
	https://socialiteproviders.netlify.com/providers/weixin.html#_3-event-listener
		vi app/Providers/EventServiceProvider
	vi config/services.php
	    'weixin' => [
	        'client_id' => env('WEIXIN_KEY'),
	        'client_secret' => env('WEIXIN_SECRET'),
	        'redirect' => env('APP_URL', 'https://abc.dev') . env('WEIXIN_REDIRECT_URI'),
	        // 这一行配置非常重要，必须要写成这个地址。
	        'auth_base_uri' => 'https://open.weixin.qq.com/connect/qrconnect',
	    ],



## users / User
	### Avatar
		$table->string('avatar')->nullable();
	### hasMany Profile 一个用户可以拥有多份用户资料
		return $this->hasMany(Profile::class);
## profiles / Profile
	php artisan make:model Models\\Profile --all

## Bulma
	npm install bulma

    <link href="https://libs.cdnjs.net/bulma/0.8.0/css/bulma.css" rel="stylesheet">

    https://eiji.dev/bulma-tabs-with-content.html

## mtvs/eloquent-hashids

## user login with mobile 

## user social login

## user role permission

## weixin/social login & weixin user profile

## Session alerts

    // $request->session()->flash('success', 'Logined!');
    // $request->session()->flash('success-info', 'More info about this alerts!');

	
composer require inertiajs/inertia-laravel tightenco/ziggy reinink/remember-query-strings
npm install @inertiajs/inertia @inertiajs/inertia-vue @babel/plugin-syntax-dynamic-import  portal-vue --save-dev

## 经验/bug
	### laravel Broadcaster config中的pusher和Pusher Channels的区别！ https://pusher.com/channels
	### tinker中生成测试数据
		$user = factory(\App\User::class, 1)->create()->first();
		$live = factory(\App\Models\Live::class, 1)->create()->first();
		
		$og = factory(\App\Models\OrganicGroup::class, 1)->create()->first();
		$og = New \App\Models\OrganicGroup
		$live = $live->members()->save($user);
		factory(\App\Models\Message::class, 1)->create() # tinker必须要第一个
		
		\App\Models\Live::create(['title' => "测试live",'description' => "Hello，直播聊天时，这里可以是一个图文结束，todo：开始时间/结束时间",'user_id' => 1,'rrule_id' => 1,]);
		\App\User::create(['name'=>'admin','email'=>'admin@admin.com','password'=>'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','email_verified_at' => now(),]);
		\App\User::create(['name'=>'test','email'=>'test@test.com','password'=>'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','email_verified_at' => now(),]);
		mysql
			update users set password="$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi"
		// Cacade delete through a polymorphic relationship
                // https://laracasts.com/discuss/channels/laravel/cacade-delete-through-a-polymorphic-relationship
	### 直播 model & Route::resource('lives', 'LiveController');  bug！
		lives的单数 ==》life了！
		live 改为 chats或
		Route::resource('lives', 'LiveController'); =》改为
		Route::resource('live', 'LiveController');
	### https css (blocked:mixed-content)
		vi Provider/AppServiceProvider.php
			URL::forceScheme('https');
	### Uncaught You must pass your app key when you instantiate Pusher.
		https://github.com/laravel/echo/issues/20 Echo won´t initiate Pusher
	### docker volume rm laravel_live_code
	 	qq-sh
	 		docker pull guoxiangke/live && docker-compose down  && docker volume rm laravel-live_code && docker-compose up -d
		git pull && docker-compose down  && docker volume rm laravel_live_code && docker-compose up -d --build && docker push guoxiangke/live

	### npm run prod bug:
		"sass-loader": "^8.0.2",
		npm update -D
		https://laracasts.com/discuss/channels/elixir/npm-run-prod-fails-with-bootstrap?page=1
	### Environment variables in Laravel Mix undefined
		https://laravel.com/docs/master/mix#environment-variables
		https://laracasts.com/discuss/channels/laravel/environment-variables-in-laravel-mix-undefined
	
	### 劝我放弃 laravel-websockets
		https://www.reddit.com/r/laravel/comments/eml0sd/issue_with_laravelwebsockets_package_over_ssl/

	### wss bug
		https://www.reddit.com/r/laravel/comments/eml0sd/issue_with_laravelwebsockets_package_over_ssl/
	
	### valet 每次必须https？用test不用dev和app
		valet domain test
		https://stackoverflow.com/questions/37135711/laravel-valet-not-working
	### vue debug
		Vue.config.devtools = true; => app.js
	### vue 动态类判断,传递参数
		v-bind:class="checkCodes(message.user_id)"
		methods: {
            checkCodes: function(user_id) {
              return user_id == this.user.id?'yes':'no';
            },
        }
	### 由于业务需要，重新切换公众号出现，redirect_uri域名与后台配置不一致，错误码10003
	### https://kmoskwiak.github.io/videojs-resolution-switcher/ on hls
	### video.js
		https://docs.videojs.com/tutorial-setup.html
	### 如何禁止h5页面播放视频时自动全屏？
		https://segmentfault.com/a/1190000012689604
			https://x5.tencent.com/tbs/guide/video.html
	### 登录后跳转之前页面
		https://stackoverflow.com/questions/15389833/laravel-redirect-back-to-original-destination-after-login
		return Redirect::intended('home')
	### js

        // var objDiv = document.getElementById("scroll");
        // objDiv.scrollTop = objDiv.scrollHeight;
        scroll.animate({ scrollTop: scroll.get(0).scrollHeight }, 1000);
        scroll.animate({ scrollTop: scroll.prop('scrollHeight') }, 1000);
	### iphone 输入框 键盘 隐藏
		https://segmentfault.com/q/1010000008281411/a-1020000008282334
		https://www.jotform.com/answers/1582331-iOS-Input-doesn-t-loose-focus-if-button-is-clicked-outside-of-iframe
	### Create Laravel Eloquent model without a primary key (Example)
		protected $primaryKey = null;
		public $incrementing = false;
	### 多对多 morphMany
		OrganicGroup
			App\Traits\Memberable;
			App\Traits\Groupable;
		//向小组添加用户
		$live->members()->create(['memberable_type'=>"App\User",'memberable_id'=>2])
		//用户加入小组
		$user->groups()->create(['groupable_type'=>"App\Models\Live",'groupable_id'=>1])
