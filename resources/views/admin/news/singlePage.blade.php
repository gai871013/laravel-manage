@extends('layouts.a')
@section('section-content')
	<div class="box box-primary">
		<div class="box-header">
			<a href="{{ route('admin.news.newsEdit',['type' => 'single']) }}" class="btn btn-sm btn-success"><i
						class="fa fa-plus"></i> @lang('admin.addSinglePage')</a>
		</div>
		<div class="box-body">
			<table class="table table-bordered table-hover">
				<tr>
					<th>@lang('admin.id')</th>
					<th>@lang('admin.title')</th>
					<th>@lang('admin.description')</th>
					<th>@lang('admin.updated_at')</th>
					<th>@lang('admin.thumb')</th>
					<th>@lang('admin.operating')</th>
				</tr>
				@foreach($lists as $k => $v)
					<tr>
						<td>{{ $v->id }}</td>
						<td>
							<a href="{{ route('page', ['id' => $v->id]) }}" target="_blank">{{ $v->title }}</a>
						</td>
						<td>{{ $v->description }}</td>
						<td>{{ $v->updated_at }}</td>
						<td>
							<img onerror="javascript:this.src='{{ asset('img/nopic.jpg') }}'"
							     src="{{ asset('storage') }}/{{ $v->thumb or '' }}" style="max-height:50px;">
						</td>
						<td>
							<a class="btn btn-success btn-xs"
							   href="{{ route('admin.news.newsEdit',['id'=>$v->id ,'type' => 'single']) }}"><i
										class="fa fa-edit"></i> @lang('admin.edit')</a>
							<a class="btn btn-danger btn-xs delete"
							   href="{{ route('admin.news.newsDelete',['id'=>$v->id]) }}"><i
										class="fa fa-trash"></i> @lang('admin.delete')</a>
						</td>
					</tr>
				@endforeach
			</table>
			<div class="row">
				{{ $lists->links() }}
			</div>
		</div>
	</div>
@endsection
@section('scripts')@endsection
