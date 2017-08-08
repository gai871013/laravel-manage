@extends('layouts.a')
@section('section-content')
	<section class="box box-primary">
		<div class="box-header">
			<a href="{{ route('admin.follower.refresh') }}" class="btn btn-sm btn-success"><i
						class="fa fa-refresh"></i> @lang('admin.refresh')@lang('admin.list')</a>
			<a href="{{ route('admin.follower.refreshDetail') }}" class="btn btn-sm btn-primary"><i
						class="fa fa-download"></i> @lang('admin.refresh')@lang('admin.details')</a>
			<a href="{{ route('admin.follower.blackLists') }}" class="btn btn-sm btn-info"><i
						class="fa fa-download"></i> @lang('admin.refresh')@lang('admin.blackList')</a>
		</div>
		<div class="box-body">
			<ul class="nav nav-tabs">
				<li @if($type == '')class="active"@endif><a
							href="{{ route('admin.follower', ['type' => '']) }}">@lang('admin.all')</a></li>
				<li @if($type == 'block')class="active"@endif><a
							href="{{ route('admin.follower', ['type' => 'block']) }}">@lang('admin.blackList')</a></li>
			</ul>

			<table class="table table-bordered table-hover table-responsive">
				<tr>
					<th>@lang('admin.id')</th>
					<th>openID</th>
					<th>@lang('admin.nickname')</th>
					<th>@lang('admin.group')</th>
					<th>@lang('admin.province')</th>
					<th>@lang('admin.city')</th>
					<th>@lang('admin.country')</th>
					<th>@lang('admin.language')</th>
					<th>@lang('admin.remark')</th>
					<th>@lang('admin.subscribe')</th>
					<th>@lang('admin.blackList')</th>
					<th>@lang('admin.subscribe')@lang('admin.time')</th>
					<th>@lang('admin.avatar')</th>
					<th>@lang('admin.operating')</th>
				</tr>
				@foreach($lists as $v)
					<tr>
						<td>{{ $v->id }}</td>
						<td>{{ $v->openid }}</td>
						<td>{{ $v->nickname }}</td>
						<td>{{ $v->group->name or '' }}</td>
						<td>{{ $v->province }}</td>
						<td>{{ $v->city }}</td>
						<td>{{ $v->country }}</td>
						<td>{{ $v->language }}</td>
						<td>{{ $v->remark }}</td>
						<td>{{ $v->subscribe == 1 ? '是' : '否' }}</td>
						<td>{{ $v->black == 1 ? '是' : '否' }}</td>
						<td>{{ date('Y-m-d H:i:s', $v->subscribe_time) }}</td>
						<td><img src="{{ $v->headimgurl or '' }}" height="20"></td>
						<td>
							<a href="{{ route('admin.follower.refreshDetail',['openid' => $v->openid]) }}"
							   class="btn btn-xs btn-primary"><i
										class="fa fa-refresh"></i> @lang('admin.refresh')@lang('admin.details')</a>
							<a href="{{ route('admin.follower.remark', ['openid' => $v->openid]) }}"
							   class="btn btn-xs btn-success remark"><i class="fa fa-edit"></i> @lang('admin.remark')
							</a>
							@if($v->black == 1)
								<a href="{{ route('admin.follower.black', ['openid' => $v->openid, 'action' => 'unBlack']) }}"
								   class="btn btn-xs btn-danger"><i
											class="fa fa-remove"></i> @lang('admin.cancel')@lang('admin.blackList')</a>
							@else
								<a href="{{ route('admin.follower.black', ['openid' => $v->openid, 'action' => 'black']) }}"
								   class="btn btn-xs btn-info"><i
											class="fa fa-check"></i> @lang('admin.join')@lang('admin.blackList')</a>
							@endif
						</td>
					</tr>
				@endforeach
			</table>
			{{ $lists->appends(['type' => $type])->links() }}
		</div>
	</section>
@endsection
@section('scripts')
	<script>
        $('.box-body img').on('click', function () {
            $this = $(this);
            layer.open({
                type: 1,
                title: false,
                closeBtn: 0,
                area: ['640px', '640px'],
                skin: 'layui-layer-rim', //没有背景色
                shadeClose: true,
                content: '<img style="max-width: 100%; margin: auto; display: block;" src="' + $this.attr('src') + '" >'
            });
        });
        $('.remark').on('click', function () {
            var $this = $(this);
            var $href = $this.attr('href');
            layer.prompt({title: '请输入备注:', formType: 2}, function (text, index) {
                layer.close(index);
//                window.location = $href + '&remark=' + text;
                $('#jump').attr('href', $href + '&remark=' + text);
                $('#jump').click();
                layer.load(1, {shade: [0.8, '#fff']})
            });
            return false;
        })
	</script>
@endsection
