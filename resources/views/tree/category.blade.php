@php
	$id = isset($id) ? $id : 0;
	$deep = isset($deep) ? (int)$deep : 1;
@endphp
@foreach($categories as &$v)

	@if($v->parent_id == $id)
		<tr>
			<td>
				<input name="info[sort][]" type="text" style="width: 50px;" value="{{ $v->sort }}">
				<input type="hidden" name="info[id][]" value="{{ $v->id }}">
			</td>
			<td>{{ $v->id }}</td>
			<td>@for($i = 1; $i < $deep; $i++)&nbsp;&nbsp;&nbsp;&nbsp;├─@endfor {{ $v->catname }}</td>
			<td>{{ $v->module }}</td>
			<td>
				<a href="{{ $v->url or route('category',['id'=>$v->id]) }}"
				   target="_blank">{{ $v->url or route('category',['id'=>$v->id]) }}</a>
			</td>
			<td>
				<a class="btn btn-xs btn-primary"
				   href="{{ route('admin.news.category.edit', ['parent_id' => $v->id]) }}"><i
							class="fa fa-plus"></i> @lang('admin.add')子@lang('admin.category')</a>
				<a class="btn btn-xs btn-success" href="{{ route('admin.news.category.edit', ['id' => $v->id]) }}"><i
							class="fa fa-edit"></i> @lang('admin.edit')</a>
				<a href="{{ route('admin.news.category.delete', ['id' => $v->id]) }}" data-id="{{ $v->id }}"
				   class="btn btn-xs btn-danger delete"><i
							class="fa fa-trash"></i> @lang('admin.delete')</a>
			</td>
		</tr>
		@if($v->child > 0)
			@php
				$deep++;
			@endphp
			@include('tree.category', ['categories'=>$categories ,'id' => $v->id])
			@php
				$deep--;
			@endphp
		@endif
	@endif
@endforeach