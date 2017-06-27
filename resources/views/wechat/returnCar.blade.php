@extends('layouts.base')
@section('title',trans('index.returnTheCar'))
@section('content')
    <div class="mb">
        <div class="cont">
            <ul class="ghcar">
                <li><span>{{ $journey->start_point }}</span></li>
                <li><span>{{ $journey->end_point }}</span></li>
            </ul>
            <ul class="ghyou">
                <li>
                    <img src="{{ asset('images/gh_icon01.png') }}">
                    {{--{{ dd($journey->data->result) }}--}}
                    <h2>{{ $journey->data->result[0]->distance->text or '' }}</h2>
                    <p>里程总计</p>
                </li>
                <li>
                    <img src="{{ asset('images/gh_icon02.png') }}">
                    <h2>0升</h2>
                    <p>油耗总计</p>
                </li>
                <li>
                    <img src="{{ asset('images/gh_icon03.png') }}">
                    <h2>0升</h2>
                    <p>剩余油量</p>
                </li>
            </ul>
        </div>
        <div class="cont">
            <div class="usecar_bs">
                <h3>如果行驶过程中存在以下支出费用，请认真填写，否则不填！</h3>
            </div>
            <form action="{{ route('weChat.returnCar') }}" method="post">
                <ul class="money">
                    <li><span>停车费</span><input placeholder="请输入停车费" name="expenses[]" value="0" type="text"/></li>
                    <li><span>过路费</span><input placeholder="请输入过路费" name="expenses[]" value="0" type="text"/></li>
                    <li><span>维修费用</span><input placeholder="请输入维修费用" name="expenses[]" value="0" type="number"/></li>
                    <li><span>特殊事故</span><input placeholder="请输入特殊事故" name="expenses[]" value="0"/></li>
                    <li><span>其它</span><input placeholder="请输入其它费用" name="expenses[]" value="0"/></li>
                </ul>
            </form>
        </div>
        <button class="btn" id="gh_btn">归还车辆</button>
    </div>


    <!--备品弹层弹层-->
    <div class="layer" id="beipin">
        <div>
            <div class="layer-content">
                <div class="layer-title">
                    <h2>温馨提示</h2>
                    <div class="close" onclick="document.getElementById('beipin').className='layer'"><i>X</i></div>
                </div>
                <div class="layer-text">
                    <h2>请检查该车辆以下备品是否齐全！</h2>
                    <ul>
                        <li>1、轮胎</li>
                        <li>2、锤子</li>
                        <li>3、钳子</li>
                    </ul>
                    <p>如果不齐全，请自行补齐后归还车辆。</p>
                </div>
                <div class="layer_button">
                    <button class="btn gray" onclick="document.getElementById('beipin').className='layer'">取消</button>
                    <button class="btn" id="sure_btn">确定</button>
                </div>

            </div>
        </div>
    </div>

    <!-- 提交成功信息弹层 -->
    <div class="layer " id="layer_succes">
        <div>
            <div class="layer-content">
                <div class="layer-title">
                    <h2>温馨提示</h2>
                    <div class="close"><i>X</i></div>
                </div>
                <div class="layer-text">
                    <p style="text-align:center;font-size:1.05rem;color:#f05136"></p>
                </div>
                <div class="layer_button">
                    <button class="btn" id="btn">确定</button>
                </div>
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

        //归还车辆
        $("#gh_btn").click(function () {
            $("#beipin").addClass("active")
        });
        //
        $("#sure_btn").click(function () {
            $('#loading').show();
            $('#beipin').hide();
            $data = $('form').serialize();
            axios.post('{{ route("weChat.returnCar") }}', $data).then(function (res) {
                $('#loading').hide();
                data = res.data;
                if (data.status_code == 20004) {
                    $.alert(data.message, function () {
                        window.location.href = '{{ route("weChat.journeys") }}';
                    });
                } else {
                    $.alert(data.message);
                }
            }).catch(function (error) {
                console.log(error);
            });
//            window.location.href = "index.html";
        })
    </script>

@endsection