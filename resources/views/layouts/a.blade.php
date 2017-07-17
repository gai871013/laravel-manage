@php
    $uri = \Route::current()->uri();
    $uri_arr = explode('/',$uri);
    $lang = end($uri_arr);
    $tit_parent = trans('admin.' . $uri_arr[1]);
    $str = 'admin.' . $lang;
    $tit = trans($str);
    $title = isset($title) ? $title : ($tit == $str ? base64_decode(env('BASE64_APP_TITLE')) : $tit);
    $icon = \App\Models\AdminAction::where('lang',$lang)->first();
@endphp
@extends('layouts.adminBase')
@section('title',$title)
@section('content')
    @component('layouts.content-header')
        @slot('title', $tit_parent)
        @slot('icon'){{ $icon->icon or '' }}@endslot
        @slot('nav', '<li>' . $tit_parent . '</li>')
        @lang($title)
    @endcomponent
    <section class="content">
        @yield('section-content')
    </section>
@endsection
@section('scripts')@endsection
