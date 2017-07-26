@extends('layouts.a')
@section('section-content')
	<div class="box box-primary">
		<div class="box-header">
			{{--<a class="btn btn-warning btn-sm" onclick="window.location=window.location.href"><i--}}
			{{--class="fa fa-refresh"></i>&nbsp;@lang('admin.refresh')</a>--}}
			<a href="{{ route('admin.news.category.edit') }}" class="btn btn-sm btn-success"><i
						class="fa fa-plus"></i> @lang('admin.add')@lang('admin.category')</a>
		</div>
		<div class="box-body">
			<form action="{{ route('admin.news.categories.sort') }}" method="post">
				{{ csrf_field() }}
				<table class="table table-bordered table-hover table-condensed">
					<tr>
						<th width="5%"></th>
						<th>@lang('admin.id')</th>
						<th>@lang('admin.category')</th>
						<th>@lang('admin.module')</th>
						<th>@lang('admin.url')</th>
						<th>@lang('admin.operating')</th>
					</tr>
					@include('tree.category',['categories' => $categories ,'id' => 0])
					<tr>
						<td colspan="6">
							<button type="submit" class="btn btn-success btn-sm"><i class="fa fa-sort-numeric-asc"></i>
								排序
							</button>
							&nbsp;从小到大排列
						</td>
					</tr>
				</table>
			</form>
		</div>
	</div>
@endsection
@section('scripts')

@endsection