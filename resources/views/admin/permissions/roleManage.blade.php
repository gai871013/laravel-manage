@extends('layouts.a')
@section('section-content')
    <div class="box box-primary">
        <div class="box-header">
            <a href="{{ route('admin.roleEdit',['id' => 0]) }}"
               class="btn btn-sm btn-success"><i class="fa fa-plus"> @lang('admin.addUser')</i></a>
        </div>
        <div class="box-body">
            <table class="table table-bordered table-responsive">
                <tr>
                    <th>@lang('admin.id')</th>
                    <th>@lang('admin.name')</th>
                    <th>@lang('admin.operating')</th>
                </tr>
                @foreach($roles as $v)
                    <tr>
                        <td>{{ $v->id }}</td>
                        <td>{{ $v->name }}</td>
                        <td>
                            @if($v->id > 1)
                                <a class="btn btn-xs btn-success"
                                   href="{{ route('admin.roleEdit',['id' => $v->id]) }}"><i
                                            class="fa fa-edit"></i> @lang('admin.edit')</a>
                                <a class="btn btn-xs btn-danger delete"
                                   href="{{ route('admin.roleDelete',['id' => $v->id]) }}"><i
                                            class="fa fa-trash"></i> @lang('admin.delete')</a>
                            @endif
                            <a href="{{ route('admin.adminManage',['role'=>$v->id]) }}" class="btn btn-xs btn-info"><i
                                        class="fa fa-eye"> @lang('admin.view')@lang('admin.user')</i></a>
                        </td>
                    </tr>
                @endforeach
            </table>
            {{ $roles->links() }}
        </div>
    </div>
@endsection
@section('scripts')@endsection
