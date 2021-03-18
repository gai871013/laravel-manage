@extends('layouts.base')
@section('title',trans('common.bindUser'))
@section('content')
    <div class="cont">
        <ul class="login">
            <li><input class="log_input" placeholder="请输入工号" id="worknum"/></li>
            <li>
                <div class="div01"><input class="input01" placeholder="请输入手机号" id="tel"/><span
                            id="timedown">获取验证码</span></div>
            </li>
            <li><input class="log_input" placeholder="请输入短信验证码" id="yzm"/></li>
        </ul>
    </div>
    <button class="btn" id="login_btn">登录</button>

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
    @include('layouts.bodyLoading')
    @include('layouts.infoLoading')
@endsection
@section('scripts')
    <script type="text/javascript">
        window.onload = function () {
            $(".loaders").hide();
        };

        //登录
        $("#login_btn").click(function () {
            var worknum = $("#worknum").val();
            var tel = $("#tel").val();
            var yzm = $("#yzm").val();
            if (worknum.length < 6) {
                $.alert("请输入正确的工号！");
                return;
            }
            if (!checkMobile(tel)) {
                $.alert("请输入正确的手机号码！");
                return;
            }

            if (yzm.length < 6 || yzm.length > 6) {
                $.alert("请输入正确的验证码！");
                return;
            }

            $("#loading").show();
            var post_data = {
                uid: worknum,
                tel: tel,
                code: yzm
            };
            axios.post('{{ route("weChat.bindUser") }}', post_data)
                .then(function (res) {
                    console.log(res);
                    $("#loading").hide();
                    if (res.data.status_code && res.data.status_code != 20002) {
                        $.alert(res.data.message);
                    } else {
                        $("#layer_succes").addClass('active').find('p').text("信息提交成功！");
                        $("#btn").click(function () {
                            $("#layer_succes").removeClass('active');
                            window.location.href = "{{ route('weChat.home') }}";
                        })
                    }
                }).catch(function (err) {
                console.log(err);
            });

        });


        //点击验证码按钮
        $("#timedown").click(function () {
            var tel = $("#tel").val();
            if (!checkMobile(tel)) {
                $.alert("请输入正确的手机号码！");
                return;
            }
            axios.post('{{ route("sendSms") }}', {tel: tel})
                .then(function (res) {
                    $.alert(res.data.message);
                    if (res.data.status_code == 40011) {
                        time($('#timedown'))
                    }
                }).catch(function (err) {
                console.log(err);
            });

        });

        //验证码倒计时
        var wait = 60;
        function time(tim) {
            if (wait == 0) {
                //o.removeAttribute("disabled");
                $(tim).css("pointer-events", "auto");
                // o.value="免费获取验证码";
                $(tim).text("获取验证码");
                wait = 60;
            } else {
                //o.setAttribute("disabled", true);
                $(tim).css("pointer-events", "none");
                // o.value="重新发送(" + wait + ")";
                $(tim).text("重新发送(" + wait + ")");
                wait--;
                setTimeout(function () {
                        time(tim)
                    },
                    1000)
            }
        }
        //document.getElementById("btn").onclick=function(){time(this);}


        //验证手机号
        function checkMobile(str) {
            if (!(/^1[3|5|7|8][0-9]\d{4,8}$/.test(str))) {
                return false;
            }
            return true;
        }
    </script>

@endsection