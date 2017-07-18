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

                @foreach($admin_action as $v)
                    @if($v->parent_id == 0)
                        <div class="portlet-body">
                            <div class="form-group">
                                <label class="col-md-2 control-label">{{Lang::get('admin.'.$v->code)}}
                                    ：</label>
                                <div class="col-md-10">
                                    @foreach($admin_action as $action)
                                        @if($action->parent_id == $v->id)
                                            <div class="col-md-3">
                                                <input type="checkbox" name="info[action_code][]"
                                                       @if(in_array( $action->id , $action_list )) checked @endif
                                                       id="action{{$action->id}}" value="{{$action->id}}"/>
                                                <label for="action{{$action->id}}"> <i
                                                            class="fa fa-{{ $action->icon }}"></i> {{Lang::get('admin.'.$action->code)}}
                                                </label>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
                @include('layouts.button', ['item' => $role])
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        });
    </script>
@endsection
