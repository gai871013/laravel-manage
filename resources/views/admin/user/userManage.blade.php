@php
    $title = isset($title) ? $title : base64_decode(env('BASE64_APP_TITLE'))
@endphp
@extends('layouts.adminBase')
@section('title',$title)
@section('content')
    @component('layouts.content-header')
        @slot('title')@lang('admin.userManage')@endslot
        @slot('icon','users')
        @slot('nav')@endslot
        @lang('admin.userManage')
    @endcomponent
    <section class="content">
        <div class="box box-primary">
            <div class="box-header">
                <a href="{{ route('admin.user.edit') }}" class="btn btn-sm btn-success"><i
                            class="fa fa-plus"></i> @lang('admin.addUser')</a>
            </div>
            <div class="box-body">
                <table class="table">
                    <tr>
                        <th>@lang('admin.id')</th>
                        <th>@lang('admin.user')</th>
                        <th>@lang('car.uid')</th>
                        <th>@lang('common.tel')</th>
                        <th>@lang('car.is_driver')</th>
                        <th>@lang('admin.operating')</th>
                    </tr>
                    @foreach($lists as $v)
                        <tr>
                            <td>{{ $v->id }}</td>
                            <td>{{ $v->name }}</td>
                            <td>{{ $v->uid }}</td>
                            <td>{{ $v->mobile }}</td>
                            <td>{{ config('car.user_driver.' . $v->is_driver ) }}</td>
                            <td>
                                <a href="{{ route('admin.user.edit',['id' => $v->id]) }}"
                                   class="btn btn-success btn-xs"><i
                                            class="fa fa-edit"></i> @lang('admin.edit')</a>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </section>
@endsection
@section('scripts')@endsection
