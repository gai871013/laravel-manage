<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - {{ config('app.name', 'LaravelManageSystem') }}</title>
    <meta name="author" content="gai871013">
    <meta name="keywords" content="{{ config('app.name', 'LaravelManageSystem') }}@yield('keywords')">
    <meta name="description" content="@yield('description')">
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('highlight/monokai-sublime.css') }}">
    <link href="{{ asset(mix('css/app.css')) }}" rel="stylesheet">
    <script src="{{ asset(mix('js/app.js')) }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.lazyload/1.9.1/jquery.lazyload.min.js"></script>
    <script src="{{ asset('highlight/highlight.pack.js') }}"></script>
    <script>hljs.initHighlightingOnLoad();</script>

    <!--[if gte IE 9]>
    <script src="{{ asset('js/jquery-1.11.1.min.js') }}"></script>
    <script src="{{ asset('js/html5shiv.min.js')  }}"></script>
    <script src="{{ asset('js/respond.min.js') }}"></script>

    <![endif]-->
    <!--[if lt IE 9]>
    <script>window.location.href = 'upgrade-browser';</script>
    <![endif]-->

    @yield('head')
</head>
<body>
<header class="header">
    <nav class="navbar navbar-default" id="navbar">
        <div class="container">
            <div class="header-topbar link-border">
                {{--<ul class="site-nav topmenu">

                    <li><a href="/links" rel="nofollow">友情链接</a></li>
                    <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" role="button"
                                            aria-haspopup="true" aria-expanded="false" rel="nofollow">关注本站 <span
                                    class="caret"></span></a>
                        <ul class="dropdown-menu header-topbar-dropdown-menu">
                            <li><a data-toggle="modal" data-target="#WeChat" rel="nofollow"><i class="fa fa-weixin"></i>
                                    微信</a></li>
                            <li><a href="http://weibo.com/5742755940" target="_blank" rel="nofollow"><i
                                            class="fa fa-weibo"></i> 微博</a></li>
                            <li><a data-toggle="modal" data-target="#qq" rel="nofollow"><i class="fa fa-qq"></i>交流群</a>
                            </li>
                        </ul>
                    </li>
                </ul>--}}

                {{--<a data-toggle="modal" data-target="#loginModal" class="login" rel="nofollow">Hi,请登录</a>&nbsp;&nbsp;--}}
                {{--<a data-toggle="modal" data-target="#regModal" class="register" rel="nofollow">我要注册</a>--}}
            </div>
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#header-navbar" aria-expanded="false"><span class="sr-only"></span> <span
                            class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span>
                </button>
                <h1 class="logo hvr-bounce-in" style="margin-top:10px;"><a href="{{ route('index') }}"
                                                                           title="">{{ config('app.name') }}</a></h1>
            </div>
            <div class="collapse navbar-collapse" id="header-navbar">
                <ul class="nav navbar-nav navbar-right">
                    <li><a data-cont="{{ config('app.name') }}" href="{{ route('index') }}">@lang('index.index')</a>
                    </li>
                    @if(isset($nav))
                        @foreach($nav as $v)
                            <li><a href="{{ route('category', ['id' => $v->id]) }}">{{ $v->catname }}</a></li>
                        @endforeach
                    @endif
                </ul>
            </div>
        </div>
    </nav>
</header>
<section class="container">
    @yield('content')
</section>
<footer class="footer">
    <div class="container">
        <p>Copyright © 2012 - {{ date('Y') }} <a
                    href="{{ route('index') }}">{{ config('app.name','LaravelManageSystem') }}</a>
            &amp; 版权所有
            <a href="https://beian.miit.gov.cn/" target="_blank">{{ base64_decode(env('BASE64_ICP')) }}</a>
        </p>
    </div>
    <div id="gotop" style="display: block;"><a class="gotop" draggable="false"></a></div>
</footer>
<!-- Scripts -->
@yield('scripts')
<div style="display: none;">
    <script src="https://s5.cnzz.com/z_stat.php?id=2984609&web_id=2984609" language="JavaScript"></script>
</div>
<script>
    //导航智能定位
    $.fn.navSmartFloat = function () {
        var position = function (element) {
            var top = element.position().top,
                pos = element.css("position");
            $(window).scroll(function () {
                var scrolls = $(this).scrollTop();
                if (scrolls > top) { //如果滚动到页面超出了当前元素element的相对页面顶部的高度
                    $('.header-topbar').fadeOut(0);
                    if (window.XMLHttpRequest) { //如果不是ie6
                        element.css({
                            position: "fixed",
                            top: 0
                        }).addClass("shadow");
                    } else { //如果是ie6
                        element.css({
                            top: scrolls
                        });
                    }
                } else {
                    $('.header-topbar').fadeIn(500);
                    element.css({
                        position: pos,
                        top: top
                    }).removeClass("shadow");
                }
            });
        };
        return $(this).each(function () {
            position($(this));
        });
    };

    //启用导航定位
    $("#navbar").navSmartFloat();
    $("img.lazyload").lazyload();
    $.ajax({
        url: '{{ route('weather') }}',
        dataType: 'json',
        success: function (res) {
            if (res.status == 1) {
                var data = res.lives[0];
                var str = data.province + '省' + data.city + ' ' + data.weather
                    + ' ,当前温度：' + data.temperature
                    + '℃, 风向：' + data.winddirection
                    + ',风力：' + data.windpower
                    + '级,湿度：' + data.humidity
                    + '%, 数据发布的时间：' + data.reporttime;
                $('.link-border').html(str);
            }
        }
    })

    $('.gotop').on('click',function(){
        window.scrollTo(0, 0);
    });

</script>
</body>
</html>
