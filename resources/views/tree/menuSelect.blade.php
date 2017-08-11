@php
	$id = isset($id) ? $id : 0;
	$deep = isset($deep) ? (int)$deep : 1;
	$url = isset($url) ? $url : url(config('app.admin_path')) . '/';
@endphp
@foreach($menu as $v)
	<option data-url="{{ $url . $v['code'] . '/' }}" @if($id == $v['id']) selected
	        @endif value="{{ $v['id'] }}">@for($i = 0; $i < $deep; $i++)&nbsp;&nbsp;&nbsp;
		&nbsp;@endfor {{ empty($v['remark']) ? trans('admin.' . $v['lang']) : $v['remark'] }}</option>
	@if(isset($v['children']))
		@php($deep++)@endphp
		@include('tree.menuSelect', ['menu' => $v['children'], 'id'=> $id, 'deep' => $deep, 'url' => $url . $v['code'] . '/'])
		@php($deep--)@endphp
	@endif
@endforeach