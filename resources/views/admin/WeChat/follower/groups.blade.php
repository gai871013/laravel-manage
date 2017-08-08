@extends('layouts.a')
@section('section-content')
    <section class="box box-primary">
        <div class="box-header">
            <a href="{{ route('admin.follower.groupUpdate') }}" data-name="" class="btn btn-sm btn-success"><i
                        class="fa fa-download"></i> @lang('admin.group')@lang('admin.update')</a>
            <a href="{{ route('admin.follower.groupEdit') }}?" class="btn btn-sm btn-primary tagEdit"><i
                        class="fa fa-plus"></i> @lang('admin.add')@lang('admin.group')</a>
        </div>
        <div class="box-body">
            <table class="table table-bordered table-hover table-responsive">
                <tr class="active">
                    <th>@lang('admin.id')</th>
                    <th>名称</th>
                    <th>@lang('admin.operating')</th>
                </tr>
                @foreach($lists as $v)
                    <tr>
                        <td>{{ $v->id }}</td>
                        <td>{{ $v->name }}</td>
                        <td>
                            <a href="{{ route('admin.follower.groupEdit', ['id' => $v->id]) }}" data-name="{{ $v->name }}"
                               class="btn btn-xs btn-success tagEdit"><i
                                        class="fa fa-edit"></i> @lang('admin.edit')</a>
                            <a href="{{ route('admin.follower.groupDelete', ['id' => $v->id]) }}"
                               class="btn btn-xs btn-danger delete"><i
                                        class="fa fa-trash"></i> @lang('admin.delete')</a>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </section>
@endsection
@section('scripts')
    <script>
        $('.tagEdit').on('click', function () {
            var $this = $(this);
            var $href = $this.attr('href');
            layer.prompt({title: '请输入标签名称:', formType: 2, value: $this.data('name')}, function (text, index) {
                layer.close(index);
//                window.location = $href + '&remark=' + text;
                $('#jump').attr('href', $href + '&remark=' + text);
                $('#jump').click();
                layer.load(1, {shade: [0.8, '#fff']})
            });
            return false;

        });
    </script>
@endsection
