@php
    $title = isset($title) ? $title : base64_decode(env('BASE64_APP_TITLE'))
@endphp
@extends('layouts.adminBase')
@section('title',$title)
@section('content')
    @component('layouts.content-header')
        @slot('title')@lang('admin.permissionsManage')@endslot
        @slot('icon','users')
        @slot('nav')@endslot
        @lang('admin.adminManage')
    @endcomponent
    <section class="content">
        <div class="box box-primary">
            <div class="box-header">
                <a href="{{ route('admin.user.profile',['id' => 0, 'next' => route('admin.adminManage')]) }}"
                   class="btn btn-sm btn-success"><i class="fa fa-plus"> @lang('admin.addUser')</i></a>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-responsive">
                    <tr>
                        <th>@lang('admin.id')</th>
                        <th>@lang('admin.username')</th>
                        <th>@lang('admin.email')</th>
                        <th>@lang('admin.name')</th>
                        <th>@lang('admin.last_ip')</th>
                        <th>@lang('admin.last_login')</th>
                        <th>@lang('admin.role')</th>
                        <th>@lang('admin.operating')</th>
                    </tr>
                    @foreach($users as $v)
                        <tr>
                            <td>{{ $v->id }}</td>
                            <td>{{ $v->username }}</td>
                            <td>{{ $v->email }}</td>
                            <td>{{ $v->name }}</td>
                            <td>{{ $v->last_ip }}</td>
                            <td>{{ $v->last_login }}</td>
                            <td>{{ $v->role->name }}</td>
                            <td>
                                <a class="btn btn-xs btn-success"
                                   href="{{ route('admin.user.profile',['id' => $v->id,'next'=>route('admin.adminManage')]) }}"><i
                                            class="fa fa-edit"></i> @lang('admin.edit')</a>
                            </td>
                        </tr>
                    @endforeach
                </table>
                {{ $users->links() }}
            </div>
        </div>
    </section>
@endsection
@section('scripts')@endsection
