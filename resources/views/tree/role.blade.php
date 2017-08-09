@foreach($action as $v)
	<li class="menu_{{ $v->parent_id or 0 }}" data-id="{{ $v->id or 0 }}">
		@for($i = 1; $i < $deep; $i++)&nbsp;&nbsp;&nbsp;&nbsp;├─@endfor
		<input type="checkbox" name="info[action_code][]"
		       @if(in_array( $v->id , $action_list )) checked @endif
		       id="action{{$v->id}}" value="{{$v->id}}"/>&nbsp;
		<label for="action{{$v->id}}"> <i
					class="fa fa-{{ $v->icon }}"></i> {{ !empty($v->remark) ? $v->remark : Lang::get('admin.'.$v->code) }}
		</label>
	</li>
	@if(isset($v->children))
		@php
			$deep++
		@endphp
		@include('tree.role', ['action' => $v->children, 'deep' => $deep])
		@php
			$deep--
		@endphp
	@endif
@endforeach