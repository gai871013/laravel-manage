@extends('layouts.app')

@section('title', isset($title) ? $title : config('app.name'))
@section('content')
    <div id="app" class="content-wrap" style="background:#fff; margin-bottom:20px; padding:10px;">


        <div style="margin-top: 15px;">
            <el-row>
                <el-col :span="12" :offset="offset">
                    <el-alert
                            title="扩展包来自：composer require gai871013/ip-location"
                            type="success"
                            description="数据来源：纯真网络。拓展包：https://gitee.com/gai871013/ip-location"
                            :closable="false">
                    </el-alert>
                </el-col>
            </el-row>
        </div>
        <div style="margin-top: 15px;">
            <el-row>
                <el-col :span="12" :offset="offset">当前IP：@{{ current }}</el-col>
            </el-row>
        </div>
        <div style="margin-top: 15px;">
            <el-row>
                <el-col :span="12" :offset="offset">

                    <el-input placeholder="请输入IP地址" v-model="ip" class="input-with-select">
                        <template slot="prepend">IP 位置查询</template>
                        <el-button slot="append" icon="el-icon-search" @click="onSubmit"></el-button>
                    </el-input>
                </el-col>
            </el-row>
        </div>
        <div style="margin-top: 15px;">
            <el-row>
                <el-col :span="12" :offset="offset">您查询的IP：@{{ info.data.ip }}</el-col>
            </el-row>
        </div>
        <div style="margin-top: 15px;">
            <el-row>
                <el-col :span="12" :offset="offset">城市：@{{ info.data.country }}</el-col>
            </el-row>
        </div>
        <div style="margin-top: 15px;">
            <el-row>
                <el-col :span="12" :offset="offset">运营商：@{{ info.data.area }}</el-col>
            </el-row>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- 引入样式 -->
    <link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">
    <!-- import Vue before Element -->
    <script src="https://unpkg.com/vue/dist/vue.js"></script>
    <!-- 引入组件库 -->
    <script src="https://unpkg.com/element-ui/lib/index.js"></script>
    <!-- axios -->
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>
        new Vue({
            el: '#app',
            data: function () {
                return {
                    ip: '{{ $ip }}',
                    current: '{{ $ip }}',
                    info: @json($info),
                    offset: 6
                }
            },
            methods: {
                onSubmit() {
                    var that = this;
                    const loading = that.$loading({
                        lock: true,
                        text: '加载中...',
                        spinner: 'el-icon-loading',
                    });
                    console.log(this.ip);
                    axios.post('{{ route('ip-result') }}', {
                        ip: this.ip,
                        _token: '{{ csrf_token() }}'
                    }).then(function (response) {
                        loading.close();
                        console.log(response);
                        if (response.data.code) {
                            that.$alert(response.data.msg, '错误提示', {
                                confirmButtonText: '确定'
                            });
                            return null;
                        }
                        that.info = response.data;
                    }).catch(function (error) {
                        console.log(error);
                    });
                }
            }
        })
    </script>
@endsection
