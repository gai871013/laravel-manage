@php
    $title = isset($title) ? $title : base64_decode(env('BASE64_APP_TITLE'))
@endphp
@extends('layouts.adminBase')
@section('title',$title)
@section('content')
    @component('layouts.content-header')
        @slot('title')@lang('admin.news')@lang('admin.manage')@endslot
        @slot('icon','book')
        @slot('nav')@endslot
        @lang('admin.categoryManage')
    @endcomponent
@endsection
@section('scripts')@endsection
