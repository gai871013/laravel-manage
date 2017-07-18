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
        @lang('admin.companyList')
    @endcomponent
@endsection
@section('scripts')@endsection
