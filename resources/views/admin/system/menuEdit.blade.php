@extends('layouts.a')
@section('section-content')
	<div class="box box-success">
		<div class="box-header"></div>
		<div class="box-body">
			<form action="{{ route('admin.system.menuEdit') }}" class="form-horizontal" method="post">
				{{ csrf_field() }}
				<div class="form-group">
					<label class="col-md-2 control-label">@lang('admin.parentMenu')：</label>
					<div class="col-md-10">
						<select name="info[parent_id]" class="form-control parent_id">
							<option data-url="{{ route('index') }}/{{ config('app.admin_path') }}/"
							        value="0">@lang('admin.level1menu')</option>
							@include('tree.menuSelect',['menu' => $menuAll ,'id' => (isset($menu->parent_id ) ? $menu->parent_id : 0),'deep' => 1])
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label">@lang('admin.title')：</label>
					<div class="col-md-10">
						<input placeholder="请输入英文" type="text" class="form-control" name="info[lang]"
						       value="{{$menu->lang or ''}}"/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label">@lang('admin.icon')：</label>
					<div class="col-md-10">
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-pencil"></i></span>
							<input style="width: 140px" type="text" id="icon" name="info[icon]"
							       value="fa-{{ $menu->icon or 'bars' }}" class="form-control icon" placeholder="输入图标"/>
						</div>
						<span class="help-block">
                                <i class="fa fa-info-circle"></i>&nbsp;For more icons please see
                                <a href="http://fontawesome.io/icons/"
                                   target="_blank">http://fontawesome.io/icons/</a>
                            </span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label">@lang('admin.dir')：</label>
					<div class="col-md-10">
						<div class="input-group">
                                <span data-basedir="{{ url(config('app.admin_path')) }}"
                                      class="input-group-addon url">{{ url(config('app.admin_path')) }}</span>
							<input type="text" class="form-control" name="info[code]"
							       value="{{$menu->code or ''}}"/>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label">@lang('admin.route')：</label>
					<div class="col-md-10">
						<input placeholder="请输入正确的路由“name”值，否则请留空" type="text" class="form-control" name="info[route]"
						       value="{{$menu->route or ''}}"/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label">@lang('admin.remark')：</label>
					<div class="col-md-10">
						<input placeholder="请输入备注" type="text" class="form-control" name="info[remark]"
						       value="{{$menu->remark or ''}}"/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label">@lang('admin.display')：</label>
					<div class="col-md-10">
						<select name="info[enable]" id="enable" class="form-control">
							<option @if(isset($menu->enable) && $menu->enable == 0) selected
							        @endif value="0">@lang('admin.no')</option>
							<option @if(isset($menu->enable) && $menu->enable == 1) selected
							        @endif value="1">@lang('admin.yes')</option>
						</select>
					</div>
				</div>
				@include('layouts.button', ['item' => $menu])
			</form>
		</div>
	</div>
@endsection
@section('scripts')
	<script>
        $(function () {
            setUrl('{{ route('admin.system.menuManage') }}');
            var val = $(this).find('option:selected').attr('data-url');
            $('.url').html(val);
            $('.parent_id').on('change', function () {
                var val = $(this).find('option:selected').attr('data-url');
//                console.log(val);
                $('.url').html(val);
            });
            $('.icon').iconpicker({placement: 'bottomLeft'});
        });
	</script>
@endsection
