@extends('layouts.base')
@section('title',trans('index.applyForCar'))
@section('content')
    <div class="mb">
        <div class="cont">
            <ul class="usecar">
                <li>
                    <span>出发地</span>
                    <input placeholder="请输入出发地" type="text" data-location="0,0" id="cfplace"/>
                    <ul id="cf_ul"></ul>
                </li>
                <li>
                    <span>目的地</span>
                    <input placeholder="请输入目的地" type="text" data-location="0,0" id="mdplace"/>
                    <ul id="md_ul"></ul>
                </li>
                <li><span>随行人数</span><input placeholder="请输入随行人数" type="text" value="0" id="peopele"/></li>
                <li><span>用车时间</span><input placeholder="请选择用车时间" class="inputtime" id="demo_date" readonly/></li>
            </ul>
        </div>
        <div class="cont">
            <div class="usecar_bs">
                <h2>任务表述</h2>
                <textarea placeholder="请输入任务描述" rows="5" id="rw_textarea"></textarea>
            </div>
        </div>
        <button class="btn" id="ti_btn">提交申请</button>
    </div>

    @include('layouts.bottom')
    @include('layouts.infoLoading')
    @include('layouts.bodyLoading')
    @include('layouts.successLoading')
@endsection
@section('scripts')
    <script type="text/javascript">
        window.onload = function () {
            $(".loaders").hide();
        };

        $('#demo_date').mobiscroll().datetime({
            mode: 'scroller',
            display: 'bottom',
            lang: 'zh',
            theme: 'material',
            dateFormat: 'yy-mm-dd',
            minDate: new Date(), //new Date(2014,0,01),
            maxDate: new Date(new Date().setMonth(new Date().getMonth() + 2))
        });

        $("#cfplace").on("input", function () {
            var data = {keyword: $(this).val()};
            axios.post('{{ route("placeSuggestion") }}', data)
                .then(function (res) {
                    if (res.data.message == 'ok') {
                        var li = '';
                        for (var i in res.data.result) {
                            li += '<li data-location="' + res.data.result[i].location.lng + ',' + res.data.result[i].location.lat + '">' + res.data.result[i].name + '</li>';
                        }
                        $('#cf_ul').html(li);
                    }
                }).catch(function (err) {
                console.log(err);
            });
            $("#cf_ul").show();
        }).blur(function () {
            $("#cf_ul").hide();
        });

        $(document).on('click', '#cf_ul li', function () {
            var tex01 = $(this).text();
            $("#cfplace").val(tex01).attr('data-location', $(this).attr('data-location'));
            $("#cf_ul").hide();
        });

        $("#mdplace").on("input", function () {
            var data = {keyword: $(this).val()};
            axios.post('{{ route("placeSuggestion") }}', data)
                .then(function (res) {
                    if (res.data.message == 'ok') {
                        var li = '';
                        for (var i in res.data.result) {
                            li += '<li data-location="' + res.data.result[i].location.lng + ',' + res.data.result[i].location.lat + '">' + res.data.result[i].name + '</li>';
                        }
                        $('#md_ul').html(li);
                    }
                }).catch(function (err) {
                console.log(err);
            });
            $("#md_ul").show();
        }).blur(function () {
            $("#md_ul").hide();
        });

        $(document).on('click', '#md_ul li', function () {
            var tex01 = $(this).text();
            $("#mdplace").val(tex01).attr('data-location', $(this).attr('data-location'));
            $("#md_ul").hide();
        });


        //提交申请
        $("#ti_btn").click(function () {
            var cfplace = $("#cfplace");
            var mdplace = $("#mdplace");
            var peopele = $("#peopele").val();
            var demo_date = $("#demo_date").val();
            var rw_textarea = $("#rw_textarea").val();

            var errMsg = '';
            if (cfplace.val().length <= 0) {
                errMsg += '请输入出发地地址!<br>';
            }
            if (cfplace.attr('data-location') == '0,0') {
                errMsg += '请根据建议选择出发地!<br>';
            }
            if (mdplace.val().length <= 0) {
                errMsg += '请输入目的地地址!<br>';
            }
            if (mdplace.attr('data-location') == '0,0') {
                errMsg += '请根据建议选择目的地!<br>';
            }
            if (peopele.length <= 0) {
                errMsg += '请输入随行人数!<br>';
            }
            if (demo_date.length < 0) {
                errMsg += '请选择用车时间<br>';
            }
            if (rw_textarea.length <= 0) {
                errMsg += '请输入任务描述!<br>';
            }
            if (errMsg.length > 0) {
                $.alert(errMsg);
                return false;
            }

            $("#loading").show();
            var data = {
                start_point: cfplace.val(),
                start_location: cfplace.attr('data-location'),
                end_point: mdplace.val(),
                end_location: mdplace.attr('data-location'),
                number: peopele,
                use_time: demo_date,
                content: rw_textarea
            };
            axios.post('{{ route("weChat.apply") }}', {info: data})
                .then(function (res) {
                    console.log(res);
                    if (res.data.status_code != 20012) {
                        $.alert(res.data.message);
                        return false;
                    }

                    $("#loading").hide();
                    $("#layer_succes").addClass('active');
                    $("#layer_succes p").text("信息提交成功！");
                    $("#btn").click(function () {
                        $("#layer_succes").removeClass('active');
                        window.location.href = "{{ route('weChat.journeys') }}";
                    })
                }).catch(function (error) {
                console.log(err);
            });

        })

    </script>

@endsection