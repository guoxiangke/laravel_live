<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>
          @if(isset($title)) {{ $title }}
          @else
            @yield('title')
          @endif
          | {{ config('app.name', 'Laravel') }}
        </title>


        <!-- Scripts -->

        <!-- Styles -->

        <!-- Fonts -->
        <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0,viewport-fit=cover">
        <meta name="wechat-enable-text-zoom-em" content="true">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="stylesheet" href="https://res.wx.qq.com/t/wx_fed/weui-source/res/2.5.14/weui.css">
        <style>
            .body {
                background-color: var(--weui-BG-0);
            }
            .weui-msg {
                min-height: 100vh;
            }
            .page__desc {
              margin-top: 4px;
              color: var(--weui-FG-1);
              text-align: left;
              font-size: 14px;
            }
        </style>

        <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>

        <!-- Scripts -->
        @vite(['resources/js/app.js'])
    </head>
    <body ontouchstart class="body">
        <div class="container" id="container">
             @yield('content')
        </div>
        
        <script type="text/javascript" src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
        <script src="https://res.wx.qq.com/t/wx_fed/cdn_libs/res/weui/1.2.8/weui.min.js"></script>

        <script type="text/javascript">
          function wxReady(callback) {
            if (
              typeof WeixinJSBridge === 'object' &&
              typeof window.WeixinJSBridge.invoke === 'function'
            ) {
              callback()
            } else {
              document.addEventListener('WeixinJSBridgeReady', callback, false)
            }
          }
          wxReady(function() {
            WeixinJSBridge.invoke('getUserConfig', {}, function(res) {
              if (res.isCareMode) {
                document.body.setAttribute('data-weui-mode','care');
              }
            });
            // 隐藏所有非基础按钮接口
            wx.hideAllNonBaseMenuItem();
            // 批量隐藏功能按钮接口
            wx.hideMenuItems({
            });

            $('#close').click(function(){
              wx.closeWindow();
            });
          });

        </script>
        @stack('modals')

    </body>
</html>
	