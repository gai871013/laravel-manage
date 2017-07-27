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
	<link href="{{ asset(mix('css/app.css')) }}" rel="stylesheet">
</head>
<body>
<header class="header">
	<nav class="navbar navbar-default shadow" id="navbar" style="position: fixed; top: 0px;">
		<div class="container">
			<div class="header-topbar hidden-xs link-border" style="display: none;">
				<ul class="site-nav topmenu">

					<li><a href="http://www.ice-breaker.cn/links" rel="nofollow" draggable="false">友情链接</a></li>
					<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" role="button"
					                        aria-haspopup="true" aria-expanded="false" rel="nofollow" draggable="false">关注本站
							<span class="caret"></span></a>
						<ul class="dropdown-menu header-topbar-dropdown-menu">
							<li><a data-toggle="modal" data-target="#WeChat" rel="nofollow" draggable="false"><i
											class="fa fa-weixin"></i> 微信</a></li>
							<li><a href="http://weibo.com/5742755940" target="_blank" rel="nofollow"
							       draggable="false"><i class="fa fa-weibo"></i> 微博</a></li>
							<li><a data-toggle="modal" data-target="#qq" rel="nofollow" draggable="false"><i
											class="fa fa-qq"></i>交流群</a></li>
						</ul>
					</li>
				</ul>

				<a data-toggle="modal" data-target="#loginModal" class="login" rel="nofollow"
				   draggable="false">Hi,请登录</a>&nbsp;&nbsp;
				<a data-toggle="modal" data-target="#regModal" class="register" rel="nofollow"
				   draggable="false">我要注册</a>
			</div>
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
				        data-target="#header-navbar" aria-expanded="false"><span class="sr-only"></span> <span
							class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span>
				</button>
				<h1 class="logo hvr-bounce-in"><a href="" title="" draggable="false"><img
								src="http://www.ice-breaker.cn/public/Home/images/logo.png" alt=""
								draggable="false"></a></h1>
			</div>
			<div class="collapse navbar-collapse" id="header-navbar">
				<ul class="nav navbar-nav navbar-right">
					<li class="hidden-index active"><a data-cont="破冰者首页" href="http://www.ice-breaker.cn"
					                                   draggable="false">破冰者首页</a></li>
					<li><a href="http://www.ice-breaker.cn/cate/1" draggable="false">前端技术</a></li>
					<li><a href="http://www.ice-breaker.cn/cate/2" draggable="false">后端程序</a></li>
					<li><a href="http://www.ice-breaker.cn/cate/3" draggable="false">linux运维</a></li>
					<li><a href="http://www.ice-breaker.cn/cate/4" draggable="false">网络安全</a></li>
					<li><a href="http://www.ice-breaker.cn/cate/5" draggable="false">程序人生</a></li>
					<li><a href="http://www.ice-breaker.cn/message" draggable="false">留言</a></li>
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
<script src="{{ asset(mix('js/app.js')) }}"></script>
</body>
</html>
