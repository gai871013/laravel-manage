@php
    $title = isset($title) ? $title : base64_decode(env('BASE64_APP_TITLE'))
@endphp
@extends('layouts.adminBase')
@section('title',$title)
@section('content')
    @component('layouts.content-header')
        @slot('title')@endslot
        @slot('icon','hand-paper-o')
        @slot('nav')@endslot
        @lang('admin.noAccess')
    @endcomponent
    <section class="content">
        <div class="box box-primary">
            <div class="box-header"></div>
            <div class="box-body">
                <div class="title">Unauthorized.</div>
                <div class="title">未授权.</div>
                <a class="btn btn-sm btn-primary" href="javascript:history.go(-1);"><i class="fa fa-backward"></i> &nbsp;返回上一页</a>
                <a class="btn btn-sm btn-danger" href="{{ route('admin.logout') }}"><i class="fa fa-user"></i> &nbsp;@lang('admin.reLogin')</a>
            </div>
        </div>
    </section>
@endsection
@section('scripts')@endsection
