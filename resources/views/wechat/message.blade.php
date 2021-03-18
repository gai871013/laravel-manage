@php
    $title = isset($title) ?$title :  '提示';
    $message = isset($message) ?$message :  '';
    $link = isset($link) ?$link :  [];
@endphp
@extends('layouts.base')
@section('title'){{ $title or env('APP_NAME') }}@endsection
@section('content')
    <div class="mb">
        <div class="newadd">
            <img src="{{ asset('images/bq.png') }}" class="bq">
            <h1>{{ $title }}</h1>
            <p>{{ $message }}</p>
            <div class="newaddbtn">
                @foreach($link as $v)<a href="{{ $v['url'] }}">{{ $v['name'] }}</a>@endforeach
            </div>
        </div>
    </div>
    @include('layouts.bottom')
    @include('layouts.infoLoading')
    @include('layouts.bodyLoading')
@endsection
@section('scripts')
    <script type="text/javascript">
        window.onload = function () {
            $(".loaders").hide();
        };
    </script>
@endsection