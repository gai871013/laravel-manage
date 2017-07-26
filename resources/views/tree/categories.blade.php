@foreach($categories as &$v)
	@if($v->parent_id == $id)
		@php
			$id = $v->id;
			$catname = $v->catname;
			$child = $v->child;
			unset($v);
		@endphp
		<li class="dd-item" data-id="{{ $id }}">
			<div class="dd-handle">
				{{ $catname or '' }}
				<span class="pull-right dd-nodrag">
            <a href="{{ route('admin.news.category.edit', ['id' => $id]) }}"><i class="fa fa-edit"></i></a>
                <a href="{{ route('admin.news.category.delete', ['id' => $id]) }}" data-id="{{ $id }}"
                   class="delete"><i
			                class="fa fa-trash"></i></a>
        </span>
			</div>
			@if($child)
				<ol class="dd-list">
					@include('tree.categories', ['categories'=>$categories ,'id' => $id])
				</ol>
			@endif
		</li>
	@endif
@endforeach