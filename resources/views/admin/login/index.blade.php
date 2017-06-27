@extends('layouts.admin')
@section('title',trans('index.login'))
@section('content')
    <div class="login-box">
        <div class="login-logo">
            <a target="_blank" href="{{ route('index') }}">{{ config('app.name') }}</a>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <form class="form-horizontal" role="form" method="POST" action="{{ route('admin.login') }}">
                {{ csrf_field() }}
                <div class="form-group has-feedback">
                    <input type="email" name="email" class="form-control" placeholder="Email">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" name="password" class="form-control" placeholder="Password">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="row">
                    <div class="col-xs-8">
                        <div class="checkbox icheck">
                            <label>
                                <input type="checkbox" name="remember"> &nbsp;@lang('index.rememberMe')
                            </label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">@lang('index.login')</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

        </div>

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
    @endif
    <!-- /.login-box-body -->
    </div>
    <!-- /.login-box -->
@endsection

@section('scripts')
    <script>
        $(function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        });
    </script>
@endsection