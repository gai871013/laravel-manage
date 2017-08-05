@extends('layouts.a')
@section('title',trans('index.tips'))
@section('section-content')
    <div class="box box-primary">
        <div class="box-header">
            <h3 class="box-title">@lang('index.tips')</h3>

            <p class="error">{{ $title or '' }}</p>
            <p class="detail">{{ $detail or '' }}</p>
            <p class="jump">
                <b id="wait">{{ $sec or 3 }}</b> @lang('admin.secondJump')
            </p>
            <div>
                <a id="href" class="btn btn-success"
                   href="{{ $next or route('admin.home') }}">@lang('admin.jumpNow')</a>
                <button id="btn-stop" class="btn btn-danger" type="button"
                        onclick="stop()">@lang('admin.stopJump')</button>
                @if(!auth('admin')->user())
                    <a id="href1" class="btn btn-primary"
                       href="{{ route('admin.login') }}">@lang('admin.reLogin')</a>
                @endif
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        (function () {
            var wait = document.getElementById('wait'), href = document.getElementById('href').href;
            var interval = setInterval(function () {
                var time = --wait.innerHTML;
                if (time <= 1) {
//                    location.href = href;
                    $('#href').click();
                    clearInterval(interval);
                }
            }, 1000);
            window.stop = function () {
                clearInterval(interval);
            };
        })();
        {!! $script or '' !!}
    </script>
@endsection