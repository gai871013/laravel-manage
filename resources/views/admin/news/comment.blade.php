@extends('layouts.a')
@section('section-content')
	<div class="box box-primary">
		<div class="box-header"></div>
		<div class="box-body">

			<table class="table table-hover table-bordered">
				<tr>
					<th>@lang('admin.id')</th>
					<th>@lang('admin.title')</th>
					<th>@lang('admin.category')</th>
					<th>@lang('admin.content')</th>
					<th>@lang('admin.comment')@lang('admin.time')</th>
					<th>@lang('admin.operating')</th>
				</tr>
				@foreach($lists as $v)
					<tr>
						<td>{{ $v->id }}</td>
						<td>{{ $v->news->title }}</td>
						<td>{{ $v->news->category->name }}</td>
						<td>{{ $v->content }}</td>
						<td>{{ $v->updated_at }}</td>
						<td></td>
						<td></td>
					</tr>
				@endforeach
			</table>
			{{ $lists->links() }}
		</div>
	</div>
@endsection