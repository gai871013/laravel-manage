@extends('layouts.a')
@section('section-content')
    <div class="box box-primary">
        <div class="box-header">
            <a href="{{ route('admin.system.operationLogsClear') }}" class="btn btn-sm btn-success"><i
                        class="fa fa-trash"></i>清除记录</a>
        </div>
        <div class="box-body">
            <table class="table table-hover table-bordered">
                <tr>
                    <th>@lang('admin.id')</th>
                    <th>@lang('admin.username')</th>
                    <th>@lang('admin.path')</th>
                    <th>@lang('admin.method')</th>
                    <th>@lang('admin.ip')</th>
                    <th>@lang('admin.params')</th>
                    <th>@lang('admin.operating')</th>
                </tr>
                @foreach($lists as $v)
                    <tr>
                        <td>{{ $v->id }}</td>
                        <td>{{ $v->admin->username or '' }}</td>
                        <td>{{ $v->path }}</td>
                        <td>{{ $v->method }}</td>
                        <td>{{ $v->ip }}</td>
                        <td><p data-input='{!! $v->input !!}'>{!! $v->input !!}</p></td>
                        <td>
                            <a class="btn btn-xs btn-danger delete" href="{{ route('admin.system.operationLogsClear', ['id'=>$v->id]) }}"><i
                                        class="fa fa-trash"></i> @lang('admin.delete')</a>
                        </td>
                    </tr>
                @endforeach
            </table>
            {{ $lists->links() }}
        </div>
    </div>
    <style>
        .table td p {
            width: 200px;
            text-overflow: ellipsis;
            overflow: hidden;
            height: 20px;
        }
    </style>
@endsection
@section('scripts')
    <script>
        $(function () {
            $('.table td p').on('click', function () {
                var $data = $(this).data('input'),
                    temp = '';
                for (var i in $data) {
                    if (typeof $data[i] == 'object') {
                        temp += i + ":<br>";
                        for (var j in $data[i]) {
                            temp += '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' + j + ":" + $data[i][j] + "<br>";
                        }
                    } else {
                        temp += i + ":" + $data[i] + "<br>";
                    }
                }
                layer.alert(temp);
            });
        });
    </script>
@endsection