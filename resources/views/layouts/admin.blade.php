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
    <script type="text/javascript">
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
    <script type="text/javascript" src="{{ asset(mix('js/admin.js')) }}"></script>
    @yield('head')
</head>
<body class="hold-transition skin-blue sidebar-mini">
@yield('content')
<!-- Scripts -->
@yield('scripts')
</body>
</html>
