@extends('layouts.app')
@section('content')
	<div class="container">
		<div class="content">
			<div class="title">@lang('index.welcome')@lang('index.access')"@lang('index.appName')"，Welcome</div>
		</div>
		<h5>
			<a href="http://www.miitbeian.gov.cn/" target="_blank">{{ base64_decode(env('BASE64_ICP')) }}</a>
		</h5>
	</div>
@endsection