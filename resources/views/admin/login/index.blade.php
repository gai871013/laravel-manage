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
					<input type="text" name="username" class="form-control" placeholder="请输入{{ config('admin.global.username') }}">
					<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
				</div>
				<div class="form-group has-feedback">
					<input type="password" name="password" class="form-control" placeholder="请输入密码">
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
	<ul class="pagination">
		@if($index > 0)
			<li><a href="{{ route('admin.login', ['index' => ($index - 1)]) }}">上一张</a></li>
		@endif
		@if($index < 7)
			<li><a href="{{ route('admin.login', ['index' => ($index + 1)]) }}">下一张</a></li>
		@endif
	</ul>
	<style>
		body {
			background: url({{ $img }});
			background-size: 100% 100%;
			overflow: hidden;
		}

		.form-horizontal {
			width: 90%;
			margin: 10px auto;
		}

		.login-logo a {
			font-weight: 900;
			color: #fff;
			text-shadow: 0 0 5px #000;
		}

		.pagination {
			position: fixed;
			bottom: 0;
			right: 15px;
		}

		.pagination > li > a {
			background: rgba(255, 255, 255, .1);
			border: 0;
			color: #fff;
		}
	</style>
@endsection

@section('scripts')
	<script>
        $(function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
            $('body').height($(window).height());
            $(window).resize(function () {
                $('body').height($(window).height());
            });
        });
	</script>
@endsection