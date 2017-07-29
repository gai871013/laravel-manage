<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>@yield('title') - {{ config('app.name', 'LaravelManageSystem') }}</title>
	<!-- Styles -->
	<link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
	<link rel="stylesheet" href="{{ asset('css/style.css') }}">
	{{--	<link href="{{ asset(mix('css/app.css')) }}" rel="stylesheet">--}}
	<script src="{{ asset(mix('js/app.js')) }}"></script>
	<script>
        $.pjax.defaults.timeout = 5000;
        $(document).pjax('a:not(a[target="_blank"],.no_pjax)', {
            container: 'body'
        });
        $(document).on('pjax:start', function () {
            layer.load(1, {shade: [0.1, '#000']});
            Pace.start();
        });
        $(document).on('pjax:end', function () {
            // Pace.stop();
            layer.closeAll();
//            layer.msg('加载完成', {offset: '90%', time: 700});
        });
        $(document).on('pjax:error', function (event, xhr) {
            layer.alert('链接错误');
        });
        $(document).on("pjax:timeout", function (event) {
            // 阻止超时导致链接跳转事件发生
            event.preventDefault()
        });

	</script>
	@yield('head')
</head>
<body>
<header class="header">
	<nav class="navbar navbar-default" id="navbar">
		<div class="container">
			<div class="header-topbar hidden-xs link-border">
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
				<h1 class="logo hvr-bounce-in"><a href="{{ route('index') }}" title="">{{ config('app.name') }}</a></h1>
			</div>
			<div class="collapse navbar-collapse" id="header-navbar">
				<ul class="nav navbar-nav navbar-right">
					<li class="hidden-index active">
						<a data-cont="{{ config('app.name') }}" href="{{ route('index') }}">@lang('index.index')</a>
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
			<a href="http://www.miitbeian.gov.cn/" target="_blank">{{ base64_decode(env('BASE64_ICP')) }}</a>
		</p>
	</div>
	<div id="gotop" style="display: block;"><a class="gotop" draggable="false"></a></div>
</footer>
<!-- Scripts -->
@yield('scripts')
</body>
</html>
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
</script>
