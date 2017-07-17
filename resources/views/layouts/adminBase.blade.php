@php($user = auth('admin')->user())
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
        $(document).on('submit', 'form', function (event) {
            $.pjax.submit(event, 'body', {fragment: 'body'});
        });
        $(document).on('pjax:start', function () {
            Pace.start();
        });
        $(document).on('pjax:end', function () {
            // Pace.stop();
        });
        $(document).on('pjax:error', function (event, xhr) {
            layer.alert('链接错误');
        });
        $(document).on("pjax:timeout", function (event) {
            // 阻止超时导致链接跳转事件发生
            event.preventDefault()
        });

    </script>
    @include('vendor.ueditor.assets')
    @yield('head')
</head>
<body class="hold-transition skin-blue sidebar-mini">

<div class="wrapper">
    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST"
          style="display: none;">
        {{ csrf_field() }}
    </form>
    <header class="main-header">
        <!-- Logo -->
        <a href="{!! url('/') !!}" target="_blank" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini">{{ env('APP_NAME','') }}</span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg">{{ env('APP_NAME','') }}</span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- User Account: style can be found in dropdown.less -->
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-user"></i>
                            <span class="hidden-xs">{{ $user->username or '' }}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <!-- Menu Footer-->
                            <li>
                                <a href="{{ route('admin.user.profile') }}"
                                   class="btn btn-default btn-flat">@lang('admin.profile')</a>
                            </li>
                            <li>
                                <a href="{{ route('logout') }}" class="btn btn-default btn-flat"
                                   onclick="event.preventDefault();
                                   document.getElementById('logout-form').submit();">安全退出</a>
                            </li>
                        </ul>
                    </li>
                    <!-- Control Sidebar Toggle Button -->
                    <li style="display: none;">
                        <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="{{ asset('img/avatar5.png') }}" class="img-circle" alt="User Image">
                </div>
                <div class="pull-left info">
                    <p>{{ $user->username or '' }}</p>
                    <a href="javascript:"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
            </div>
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu">
                @include('admin.leftMenu',['navAll'=>\App\Helpers\Helper::leftMenu('admin')]);
            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>
    <div class="content-wrapper">
        <section class="col-xs-12">
            @include('flash::message')
            <style>.alert {
                    margin: 20px 0 0;
                }</style>
        </section>
        <div class="clearfix"></div>
        @yield('content')
    </div>
    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>Version</b> {{ config('app.version', 'v1.0') }}
        </div>
        <strong>Copyright &copy; 2016-{{ date('Y') }} <a target="_blank" href="http://weibo.com/gai871013">gai871013</a>&nbsp;<a
                    href="http://www.wc87.com" target="_blank">WC87.COM</a>.</strong> All rights
        reserved.
    </footer>
</div>
<!-- Scripts -->
<script src="{{ asset('js/fontawesome-iconpicker.js') }}"></script>
@yield('scripts')
<a href="" id="jump"></a>
<script type="text/javascript">
    $(function () {
        $('.content-wrapper').css('min-height', $(window).height() - 101);
        // select2
        $(".select2").select2();
        // 提交验证按钮
        $('.comfirm').on('click', function () {
            var errMsg = [];
            $('.form-filter').each(function (i, s) {
                if ($(this).val() == '') {
                    var str = $(this).parents('.form-group').find('.control-label').text();
                    str = str.trim();
                    errMsg.push(str);
                }
            });
            if (errMsg.length > 0) {
                layer.alert('<b>以下选项为必填：</b><br>' + errMsg.join('<br>'));
                return false;
            }
            $(this).attr("disabled", "disabled").parents('form').submit();
        });
        // 左侧菜单
        $('.sidebar-menu a').each(function (index) {
            $href = $(this).attr('href');
            _href = (typeof _href == 'undefined') ? '' : window._href;
            if ($href == window.location.href || $href == _href) {
                $(this).parents('.treeview').addClass('active');
                $(this).parent('li').addClass('active').siblings().removeClass('active');
                return false;
            }
        });
        // 删除链接
        $('.delete').on('click', function () {
            var _this = this;
            $lang = $(_this).attr('data-lang') || '@lang("admin.confirmDelete")？';
            layer.confirm($lang, {
                btn: ['确定', '取消'] //按钮
            }, function () {
                layer.msg('@lang("admin.inOperation")...');
                $jump = $('#jump');
                $jump.attr('href', $(_this).attr('href'));
                $jump.click();
            }, function () {
                return true;
            });
            return false;
        });
        // 删除按钮(批量删除)
        $('.deleteBtn').on('click', function () {
            $lang = $(this).attr('data-lang') || '@lang("admin.confirmDelete")？';
            if (confirm($lang)) {
                return true;
            } else {
                return false;
            }
        });
        // 一次通知
        $('#flash-overlay-modal').modal();
        $('div.alert').not('.alert-important').delay(3000).fadeOut(350);
    });
</script>
</body>
</html>
