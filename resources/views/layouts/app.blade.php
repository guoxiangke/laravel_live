<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('style')
</head>
<body>
    <div id="app"  class="container">
        @include('layouts.nav')
        
        @include('partials.alerts')
        
        <div class="d-blocks">
            @yield('content')
        </div> <!-- end d-blocks -->
    </div> <!-- end app -->

    <script type="text/javascript">
        window.onload = function () {
            $('.notification').on('click',function(){
                $(this).slideUp('slowly');
            });
            setTimeout(function(){ $('.notification').slideUp('slowly')}, 3000);

            // Check for click events on the navbar burger icon
            $(".navbar-burger").click(function() {
              // Toggle the "is-active" class on both the "navbar-burger" and the "navbar-menu"
              $(".navbar-burger").toggleClass("is-active");
              $(".navbar-menu").toggleClass("is-active");
            });


            $('a.btn-confirm').click(function(e){
                let msg = $(this).attr('data-confirm');
                msg = typeof(msg)=='undefined'?'Are you sure!':msg;
                if (!confirm(msg)) {
                    e.preventDefault();
                }
            });

            $('.submit-confirm').click(function(e){
              e.preventDefault();
              let msg = $(this).attr('data-confirm');
              msg = typeof(msg)=='undefined'?'Are you sure!':msg;
              if (confirm(msg)) {
                  $(this).parent('form').submit();
              }
            });
          };
    </script>
    @yield('script')
</body>
</html>
