<!DOCTYPE html>
<html>
<head>
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0,user-scalable=no"
          name="viewport" id="viewport"/>
    <title>@yield('title')</title>
    <meta content="yes" name="apple-mobile-web-app-capable"/>
    <meta content="telephone=no" name="format-detection"/>
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta name="author" content="gai871013"/>
    <meta name="keywords" content="{{ base64_decode(env('BASE64_APP_KEYWORDS','')) }}@yield('keywords')">
    <meta name="description" content="{{ base64_decode(env('BASE64_APP_DESCRIPTION','')) }}@yield('description')">
    <!-- CSRF Token -->
    <meta name="csrf-token" id="crsf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset(mix('css/app.css')) }}">
    @yield('head')
</head>
<body>
@yield('content')
<script type="text/javascript" src="{{ asset(mix('js/app.js')) }}"></script>
@yield('scripts')
</body>
</html>