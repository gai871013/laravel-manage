@extends('layouts.base')
@section('title',trans('index.index'))
@section('content')
    <div class="mb">
        <div class="bg01">
            <h1 class="tith1">用车请申请</h1>
            <div class="car_div">
                <div class="car">
                    <img src="{{ asset('images/car_body.png') }}" class="carbody" alt="车身">
                    <img src="{{ asset('images/car_cl.png') }}" class="carcl01" alt="车身">
                    <img src="{{ asset('images/car_cl.png') }}" class="carcl02" alt="车身">
                </div>

            </div>
        </div>
        <img src="{{ asset('images/logo01.png') }}" class="bglogo01">
        <img src="{{ asset('images/logo02.png') }}" class="bglogo02">
        <button class="btn01"
                id="index_btn">@if($return) @lang('index.returnTheCar') @else @lang('index.applyForCar') @endif</button>
    </div>
    @include('layouts.bottom')
    @include('layouts.bodyLoading')
@endsection
@section('scripts')
    <script>
        window.onload = function () {
            $(".loaders").hide();
        };
        $("#index_btn").click(function () {
            $('.loaders').show(0);
            window.location.href = "@if($return){{ route('weChat.returnCar') }}@else{{ route('weChat.apply') }}@endif"
        })
    </script>
@endsection