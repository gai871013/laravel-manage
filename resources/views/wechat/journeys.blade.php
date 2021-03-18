@extends('layouts.base')
@section('title',trans('index.myJourney'))
@section('content')
    @if($journeys->count() == 0)@lang('car.no_journey')@endif
    <div class="main" id="contId01">

        <div class="mb">
            <div class="xclist">
                @foreach($journeys as $v)
                    <dl class="journeys" data-id="{{ $v->id }}">
                        <dt><span>{{ $v->created_at }}</span><em>{{ config('car.journey.'.$v->status) }}</em></dt>
                        <dd><span>{{ $v->start_point }}</span></dd>
                        <dd><span>{{ $v->end_point }}</span></dd>
                    </dl>
                @endforeach
            </div>
            <div class="more on" id="loading01" style="pointer-events:none; display: none;"><span>加载中</span></div>
        </div>
    </div>

    <div class="main mb detail" id="contId02">
        <div class="cont">
            <div class="xclist">
                <dl>
                    <dt><span class="time"></span></dt>
                    <dd><span class="start_point"></span></dd>
                    <dd><span class="end_point"></span></dd>
                </dl>
            </div>
            <ul class="ghyou" style="display: none; border-top: 1px solid #eee; padding-top: 14px; margin-top: 0">
                <li>
                    <img src="{{ asset('images/gh_icon01.png') }}">
                    <h2>11.20公里</h2>
                    <p>里程总计</p>
                </li>
                <li>
                    <img src="{{ asset('images/gh_icon02.png') }}">
                    <h2>25升</h2>
                    <p>油耗总计</p>
                </li>
                <li>
                    <img src="{{ asset('images/gh_icon03.png') }}">
                    <h2>12升</h2>
                    <p>剩余油量</p>
                </li>
            </ul>
        </div>
        <div class="cont">
            <div class="usecar_bs">
                <h1>支出费用</h1>
            </div>
            <ul class="ghyou expenses">
                <li>
                    <img src="{{ asset('images/fei_icon01.png') }}">
                    <h2>0元</h2>
                    <p>停车费</p>
                </li>
                <li>
                    <img src="{{ asset('images/fei_icon02.png') }}">
                    <h2>0元</h2>
                    <p>过路费</p>
                </li>
                <li>
                    <img src="{{ asset('images/fei_icon03.png') }}">
                    <h2>0元</h2>
                    <p>维修费用</p>
                </li>
                <li>
                    <img src="{{ asset('images/fei_icon04.png') }}">
                    <h2>0元</h2>
                    <p>特殊事故</p>
                </li>
                <li>
                    <img src="{{ asset('images/fei_icon05.png')  }}">
                    <h2>0元</h2>
                    <p>其它</p>
                </li>

            </ul>
        </div>
        <button class="btn" style="display: none;" id="gh_btn">归还车辆</button>
    </div>
    @include('layouts.bottom',['index'=>2])
    @include('layouts.infoLoading')
    @include('layouts.bodyLoading')

@endsection
@section('scripts')
    <script type="text/javascript">
        window.onload = function () {
            id = location.hash.substr(1);
            $(".loaders").hide();
            location.hash = 'contId01';
            $(".main").hide();
            $("#" + id).show();
        };

        $(window).on('hashchange', function (e) {
            id = location.hash.substr(1);
            $(".main").hide();
            $("#" + id).show();
        });


        //归还车辆
        $("#gh_btn").click(function () {
            $("#beipin").addClass("active");
        });
        //
        $("#sure_btn").click(function () {
            window.location.href = "{{ route('weChat.returnCar') }}";
        });

        $(".tablist").hide().eq(0).show();
        $(".tab li").click(function () {
            var i = $(this).index();
            $(this).addClass("on");
            $(this).siblings().removeClass("on");
            $(".tablist").hide().eq(i).show();
        }).eq(0).addClass("on");

        $(".journeys").click(function () {
            $this = $(this);
            $('.loaders').show();
            // todo 储存远程获取数据，不必每次都获取
            axios.get('{{ route("weChat.journeyDetail") }}', {
                params: {
                    id: $this.attr('data-id')
                }
            }).then(function (res) {
                $('.loaders').hide();
                data = res.data;
                $('.time').html(data.created_at);
                $('.start_point').html(data.start_point);
                $('.end_point').html(data.end_point);

                $('.expenses li h2').html('0.00元');
                for (var i in data.expenses) {
                    if (data.expenses.hasOwnProperty(i)) {
                        $fee = data.expenses[i].fee || 0;
                        $('.expenses li').eq(i).find('h2').html($fee + '元');
                    }
                }
                // 判断是否使用中
                if (data.status == 1) {
                    $('#gh_btn').show();
                }
                location.hash = 'contId02';
                $(".main").hide();
                $('#contId02').show();
            }).catch(function (error) {
                console.log(error);
            });

        });

        $(function () {
            $('#gh_btn').on('click', function () {
                window.location.href = '{{ route("weChat.returnCar") }}';
            });
        });

    </script>
@endsection