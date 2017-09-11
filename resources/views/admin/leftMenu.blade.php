@foreach($navAll as $k => $v)
	<li class="treeview">
		@php
			$url = $v['route'] ? route($v['route']) : url($uri . $v['code']);
		@endphp
		<a href="@if(isset($v['children']))javascript:;@else{{ $url }}@endif">
			<i class="fa fa-{{ $v['icon'] }}"></i>
			<span>{{ !empty($v['remark']) ? $v['remark'] : trans('admin.'.$v['lang']).trans('admin.manage') }}</span>
			@if(isset($v['children']))
				<span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
			@endif
		</a>
		@if(isset($v['children']))
			<ul class="treeview-menu">
				@include('admin.leftMenu', ['navAll' => $v['children'], 'uri' => $url . '/'])
			</ul>
		@endif
	</li>
@endforeach