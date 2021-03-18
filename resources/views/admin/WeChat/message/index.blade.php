@extends('layouts.a')
@section('section-content')
	<div class="box box-primary">
		<div class="box-header">
			<a href="{{ route('admin.WeChat.message.subscribe') }}" class="btn btn-sm btn-success"><i class="fa fa-eye"></i> 关注回复</a>
		</div>
		<div class="box-body"></div>
	</div>
@endsection