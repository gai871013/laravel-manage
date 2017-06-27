@php
    $title = isset($title) ? $title : base64_decode(env('BASE64_APP_TITLE'))
@endphp
@extends('layouts.adminBase')
@section('title',$title)
@section('content')
    @component('layouts.content-header')
        @slot('title')@lang('admin.userManage')@endslot
        @slot('icon','edit')
        @slot('nav')@endslot
        @lang('admin.edit')
    @endcomponent
    <section class="content">
        <div class="box box-primary">
            <div class="box-header"></div>
            <div class="box-body">
                <form action="{{ route('admin.user.editAction') }}" method="post" id="editAction"
                      class="form-horizontal">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="col-md-2 control-label">@lang('user.name'):
                            <span class="required">*</span>
                        </label>
                        <div class="col-md-10">
                            <input type="text" class="form-control form-filter"
                                   name="info[name]"
                                   value="{{ $user->name or '' }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">@lang('user.nickname'):
                            <span class="required">*</span>
                        </label>
                        <div class="col-md-10">
                            <input type="text" class="form-control form-filter"
                                   name="info[nickname]"
                                   value="{{ $user->nickname or '' }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">@lang('user.username'):
                            <span class="required">*</span>
                        </label>
                        <div class="col-md-10">
                            <input type="text" class="form-control form-filter"
                                   name="info[username]"
                                   value="{{ $user->username or '' }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">@lang('user.email'):
                            <span class="required">*</span>
                        </label>
                        <div class="col-md-10">
                            <input type="text" class="form-control form-filter"
                                   name="info[email]"
                                   value="{{ $user->email or '' }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">@lang('user.sex'):
                            <span class="required">*</span>
                        </label>
                        <div class="col-md-10">
                            <select name="info[sex]" class="form-control select2">
                                <option @if(isset($user->sex) && $user->sex == 1) selected @endif value="1">男</option>
                                <option @if(isset($user->sex) && $user->sex == 2) selected @endif value="2">女</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">@lang('user.uid'):
                            <span class="required">*</span>
                        </label>
                        <div class="col-md-10">
                            <input type="text" class="form-control form-filter"
                                   name="info[uid]"
                                   value="{{ $user->uid or '' }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">@lang('user.mobile'):
                            <span class="required">*</span>
                        </label>
                        <div class="col-md-10">
                            <input type="text" class="form-control form-filter"
                                   name="info[mobile]"
                                   value="{{ $user->mobile or '' }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">@lang('user.is_driver'):
                            <span class="required">*</span>
                        </label>
                        <div class="col-md-10">
                            <select name="info[is_driver]" class="form-control">
                                <option @if(isset($user->is_driver) && $user->is_driver == 1) selected @endif value="1">是</option>
                                <option @if(isset($user->is_driver) and $user->is_driver == 0) selected @endif value="0">否</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">@lang('user.card_address'):
                            <span class="required">*</span>
                        </label>
                        <div class="col-md-10">
                            <input type="text" class="form-control form-filter"
                                   name="info[card_address]"
                                   value="{{ $user->card_address or '' }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">@lang('user.address'):
                            <span class="required">*</span>
                        </label>
                        <div class="col-md-10">
                            <input type="text" class="form-control form-filter"
                                   name="info[address]"
                                   value="{{ $user->address or '' }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">@lang('user.license_date'):
                            <span class="required">*</span>
                        </label>
                        <div class="col-md-10">
                            <input type="text" class="form-control form-filter"
                                   data-toggle="datepicker"
                                   name="info[license_date]"
                                   value="{{ $user->license_date or '' }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">@lang('user.driving_age'):
                            <span class="required">*</span>
                        </label>
                        <div class="col-md-10">
                            <input type="text" class="form-control form-filter"
                                   name="info[driving_age]"
                                   value="{{ $user->driving_age or '' }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">@lang('user.quasi_driving_type'):
                            <span class="required">*</span>
                        </label>
                        <div class="col-md-10">
                            <input type="text" class="form-control form-filter"
                                   name="info[quasi_driving_type]"
                                   value="{{ $user->quasi_driving_type or '' }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">@lang('user.birthday'):
                            <span class="required">*</span>
                        </label>
                        <div class="col-md-10">
                            <input type="text" class="form-control form-filter"
                                   data-toggle="datepicker"
                                   name="info[birthday]"
                                   value="{{ $user->birthday or '' }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">@lang('user.id_number'):
                            <span class="required">*</span>
                        </label>
                        <div class="col-md-10">
                            <input type="text" class="form-control form-filter"
                                   name="info[id_number]"
                                   value="{{ $user->id_number or '' }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">@lang('user.license_number'):
                            <span class="required">*</span>
                        </label>
                        <div class="col-md-10">
                            <input type="text" class="form-control form-filter"
                                   name="info[license_number]"
                                   value="{{ $user->license_number or '' }}">
                        </div>
                    </div>

                    <input type="hidden" name="info[id]" value="{{ $user->id or 0 }}">
                </form>

                <div class="form-group">
                    <div class="col-md-10 col-md-offset-2">

                        <div class="actions btn-set">
                            <a type="button" name="back" onclick="window.history.go(-1)"
                               class="btn btn-default"><i
                                        class="fa fa-angle-left"></i> 返回</a>
                            <button class="btn btn-default" type="button"
                                    onclick="document.getElementById('editAction').reset()"><i
                                        class="fa fa-refresh"></i> 重置
                            </button>
                            <button class="btn btn-success ajax-post no-refresh comfirm" type="submit">
                                <i class="fa fa-check"></i> 保存
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
    <script>
        $(function () {
            $(".select2").select2();
//            Inputmask().mask(document.querySelectorAll("input"));
            $('[data-toggle="datepicker"]').datepicker({
                format: 'yyyy-mm-dd',
                endDate: '{{ date("Y-m-d") }}',
                autoHide: true,
                language: 'zh-CN'
            });
            $('.comfirm').on('click', function () {
                var errMsg = [];
                $('.form-filter').each(function (i, s) {
                    if ($(this).val() == '') {
                        var str = $(this).parents('.form-group').find('.control-label').text();
                        str = str.trim();
                        errMsg.push(str);
                    }
//                    console.log(i, );
                });
                if (errMsg.length > 0) {
                    layer.alert('<b>以下选项为必填：</b><br>' + errMsg.join('<br>'));
                    return false;
                }
                $('#editAction').submit();
            });
        });
    </script>
@endsection
