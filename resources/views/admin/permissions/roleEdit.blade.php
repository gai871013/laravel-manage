@extends('layouts.a')
@section('section-content')
	<div class="box box-primary">
		<div class="box-header"></div>
		<div class="box-body">
			<form action="{{ route('admin.roleEdit') }}" class="form-horizontal" method="post">
				{{ csrf_field() }}

				<div class="form-group">
					<label class="col-md-2 control-label">角色名称：</label>
					<div class="col-md-10">
						<input type="text" class="form-control" name="info[name]"
						       value="{{$role->name or ''}}"/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label">角色描述：</label>
					<div class="col-md-10">
                            <textarea class="form-control"
                                      name="info[describe]">{{$role->describe or ''}}</textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label">权限：</label>
					<div class="col-md-10">
						<ul style="list-style: none; padding: 0;" class="menu">
							@include('tree.role', ['action' => $admin_action, 'deep' => 1])
						</ul>
					</div>
				</div>
				@include('layouts.button', ['item' => $role])
			</form>
		</div>
	</div>
@endsection
@section('scripts')
	<script>
        setUrl('{{ route('admin.roleManage') }}');
        $(function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            }).on('ifChecked', function (event) {
                $this = $(this);
                $id = $this.parents('li').data('id');
                $('.menu_' + $id).find('input').iCheck('check');
            }).on('ifUnchecked', function (event) {
                $this = $(this);
                $id = $this.parents('li').data('id');
                $('.menu_' + $id).find('input').iCheck('uncheck');
            });
        });
	</script>
@endsection
