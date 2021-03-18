@php
	$id = isset($id) ? $id : 0;
	$deep = isset($deep) ? (int)$deep : 1;
@endphp
@foreach($categories as &$v)
	@php
		$_id = $v->id;
		$parent_id = $v->parent_id;
		$catname = $v->catname;
		$child = $v->child;
		unset($v);
	@endphp
	@if($parent == $parent_id)
		<option @if($id == $_id) selected
		        @endif value="{{ $_id }}">
			@for($i = 1; $i < $deep; $i++)&nbsp;&nbsp;&nbsp;&nbsp;├─@endfor {{ $catname }}
		</option>
		@if($child > 0)
			@php
				$deep++;
			@endphp
			@include('tree.categoriesSelect', ['categories' => $categories, 'id'=> $id, 'deep' => $deep ,'parent' => $_id])
			@php
				$deep--;
			@endphp
		@endif
	@endif
@endforeach