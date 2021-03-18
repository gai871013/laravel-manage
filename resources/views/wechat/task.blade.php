@extends('layouts.base')
@section('title',trans('index.myTask'))
@section('content')
    <div class="main" id="contId01">
        <div class="tab">
            <ul>
                <li>待完成</li>
                <li>已完成</li>
            </ul>
        </div>
        <div class="mb mt">
            <div class="tablist">
                @foreach($task as $v)
                    @if($v->status == 0)
                        <dl data-id="{{ $v->id }}">
                            <dd><span>出发地</span><em>{{ $v->journey->start_point }}</em></dd>
                            <dd><span>用车时间</span><em>{{ $v->use_time }}</em></dd>
                        </dl>
                    @endif
                @endforeach
            </div>

            <div class="tablist">
                @foreach($task as $v)
                    @if($v->status == 1)
                        <dl data-id="{{ $v->id }}">
                            <dd><span>出发地</span><em>{{ $v->journey->start_point }}</em></dd>
                            <dd><span>完成时间</span><em>{{ $v->completed_at }}</em></dd>
                        </dl>
                    @endif
                @endforeach
            </div>
            <div class="more on" id="loading01" style="pointer-events:none; display: none;"><span>加载中</span></div>
        </div>
    </div>

    <div class="main" id="contId02">
        <div class="cont">
            <ul class="tabcont">
                <li><span>任务单号</span><em class="task_id"></em></li>
                <li><span>车牌号</span><em class="task_number"></em></li>
                <li><span>用车单位</span><em class="task_company"></em></li>
                <li><span>用车时间</span><em class="task_use_time"></em></li>
                <li><span>完成时间</span><em class="task_completed_at"></em></li>
                <li><span>报道地点</span><em class="task_point"></em></li>
            </ul>
        </div>
        <div class="cont">
            <div class="usecar_bs">
                <h2>任务表述</h2>
                <div class="beizh01"></div>
            </div>
        </div>
        <button class="btn task_submit" style="display: none;">完成任务</button>
    </div>
    @include('layouts.bottom',['index'=>1])
    @include('layouts.infoLoading')
    @include('layouts.bodyLoading')
    <!--配品检查弹层-->
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

@endsection
@section('scripts')
    <script type="text/javascript">
        window.onload = function () {
            id = location.hash.substr(1);
            $(".loaders").hide();
            location.hash = 'contId01';
            $(".main").hide();
            $("#" + id).show()
        };

        $(window).on('hashchange', function (e) {
            id = location.hash.substr(1);
            $(".main").hide();
            $("#" + id).show();
            console.log(id);
        });


        //归还车辆
        $(".task_submit").click(function () {
            $('#loading').show();
            axios.post('{{ route('weChat.taskFinish') }}', {id: $('.task_id').text()})
                .then(function (res) {
                    data = res.data;
                    if (data.status_code == 20004) {
                        $.alert(data.message, function () {
                            window.location.href = data.url;
                        });
                    } else {
                        $.alert(data.message);
                    }
                }).catch(function (error) {
                console.log(error);
            })
//            $("#beipin").addClass("active")
        });

        $(".tablist").hide().eq(0).show();
        $(".tab li").click(function () {
            var i = $(this).index();
            $(this).addClass("on");
            $(this).siblings().removeClass("on");
            $(".tablist").hide().eq(i).show();
        }).eq(0).addClass("on");

        $(".tablist dl").click(function () {
            $this = $(this);
            $('.loaders').show();
            axios.get('{{ route("weChat.taskDetail") }}', {
                params: {
                    id: $this.attr('data-id')
                }
            }).then(function (res) {
                $('.loaders').hide();
                data = res.data;
                if (!data.car) {
                    $.alert('尚未分配车辆');
                    $('.task_number').html('');
                } else {
                    $('.task_number').html(data.car.plate_number);
                }
                $('.task_id').html(data.id || 0);
                $('.task_company').html(data.company_name || '');
                $('.task_use_time').html(data.use_time || new Date());
                $('.task_completed_at').html(data.completed_at || '未完成');
                $('.task_point').html(data.point || '无');
                $('.beizh01').html(data.content || '');
                // 判断是否显示“完成任务”按钮
                if (data.journey.status === 1 && data.status === 0) {
                    $('.task_submit').show();
                }
                location.hash = 'contId02';
                $(".main").hide();
                $('#contId02').show();
            }).catch(function (error) {
                console.log(error);
            });
        })
    </script>

@endsection