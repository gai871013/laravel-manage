@extends('layouts.adminBase')
@section('title',$title)
@section('content')
    @component('layouts.content-header')
        @slot('title')@lang('admin.backstage')@lang('index.index')@endslot
        @slot('icon','dashboard')
        @slot('nav') @endslot
        @lang('index.index')
    @endcomponent

    <!-- Main content -->
    <section class="content">
        <div class="box box-primary">
            <div class="box-header">
                <p>登录次数：{{ $user->login_num or 0 }} </p>
                <p>上次登录IP：{{ $user->last_ip or '' }} 上次登录时间：{{ $user->last_login or '' }}</p>
            </div>
            <div class="box-body">
                <a class="btn btn-app">
                    <span class="badge bg-yellow">0</span>
                    <i class="fa fa-bullhorn"></i> Notifications
                </a>
                <a class="btn btn-app">
                    <span class="badge bg-aqua">0</span>
                    <i class="fa fa-envelope"></i> Inbox
                </a>
                <a class="btn btn-app">
                    <span class="badge bg-purple">0</span>
                    <i class="fa fa-users"></i> @lang('admin.user')</a>

                <div class="widget-box">
                    <div class="widget-content nopadding">
                        <table class="table table-bordered table-striped with-check">
                            <tr>
                                <th colspan="2" scope="col">服务器信息</th>
                            </tr>
                            <tr>
                                <td style="width:20%;">服务器域名/IP地址</td>
                                <td>{{ @get_current_user() }} - {{ $_SERVER['SERVER_NAME'] }}（
                                    @if ('/' == DIRECTORY_SEPARATOR)
                                        {{ $_SERVER['SERVER_ADDR'] }}
                                    @else
                                        {{ @gethostbyname($_SERVER['SERVER_NAME']) }}
                                    @endif
                                    ）&nbsp;&nbsp;你的IP地址是：{{ \EasyWeChat\Payment\get_client_ip() }}</td>
                            </tr>
                            <tr>
                                <td>服务器操作系统</td>
                                <td><?php $os = explode(" ", php_uname());
                                    echo $os[0]; ?> &nbsp;内核版本：<?php if ('/' == DIRECTORY_SEPARATOR) {
                                        echo $os[2];
                                    } else {
                                        echo $os[1];
                                    } ?></td>
                            </tr>
                            <tr>
                                <td>服务器解译引擎</td>
                                <td>{{ $_SERVER['SERVER_SOFTWARE'] }}</td>
                            </tr>
                            <tr>
                                <td>服务器语言</td>
                                <td>{{ getenv("HTTP_ACCEPT_LANGUAGE") }}</td>
                            </tr>
                            <tr>
                                <td>服务器端口</td>
                                <td>{{ $_SERVER['SERVER_PORT'] }}</td>
                            </tr>
                            <tr>
                                <td>服务器主机名</td>
                                <td><?php if ('/' == DIRECTORY_SEPARATOR) {
                                        echo $os[1];
                                    } else {
                                        echo $os[2];
                                    } ?></td>
                            </tr>
                            <tr>
                                <td>绝对路径</td>
                                <td>{{ base_path() }}</td>
                            </tr>
                            <tr>
                                <td>管理员邮箱</td>
                                <td><?php echo @$_SERVER['SERVER_ADMIN']; ?></td>
                            </tr>
                            <tr>
                                <td>程序路径</td>
                                <td>{{ app_path() }}</td>
                            </tr>

                        </table>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection