@foreach($categories as &$v)

	@if($v->parent_id == $id)
		@php
			$_id = $v->id;
			$catname = $v->catname;
			$child = $v->child;
			unset($v);
		@endphp
		<li class="dd-item" data-id="{{ $_id }}">
			<div class="dd-handle">
				{{ $catname or '' }}
				<span class="pull-right dd-nodrag">
            <a href="{{ route('admin.news.category.edit', ['id' => $_id]) }}"><i class="fa fa-edit"></i></a>
                <a href="{{ route('admin.news.category.delete', ['id' => $_id]) }}" data-id="{{ $_id }}"
                   class="delete"><i
			                class="fa fa-trash"></i></a>
        </span>
			</div>
			@if($child > 0)
				<ol class="dd-list">
					@include('tree.categories', ['categories'=>$categories ,'id' => $_id])
				</ol>
			@endif
		</li>
	@endif
@endforeach