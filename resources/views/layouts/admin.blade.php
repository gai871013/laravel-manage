<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>@yield('title') - {{ config('app.name', trans('index.title')) }}</title>
	<meta name="author" content="gai871013">
	<meta name="keywords" content="{{ base64_decode(env('BASE64_APP_KEYWORDS','')) }}@yield('keywords')">
	<meta name="description" content="{{ base64_decode(env('BASE64_APP_DESCRIPTION','')) }}@yield('description')">

	<!-- Styles -->
	<link type="text/css" href="{{ asset(mix('css/admin.css')) }}" rel="stylesheet">
	<link type="text/css" href="{{ asset(mix('css/AdminLTE.css')) }}" rel="stylesheet">

	<!-- Scripts -->
	<script type="text/javascript" src="{{ asset(mix('js/admin.js')) }}"></script>
	<script>
        $.pjax.defaults.timeout = 5000;
        $(document).pjax('a:not(a[target="_blank"],.no_pjax)', {
            container: 'body'
        });
        $(document).on('pjax:start', function () {
            layer.load(1,{shade: [0.1,'#000']});
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
<body class="hold-transition skin-blue sidebar-mini">
@yield('content')
<!-- Scripts -->
@yield('scripts')
</body>
</html>
