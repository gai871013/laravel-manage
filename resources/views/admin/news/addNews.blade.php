@php
    $title = isset($title) ? $title : base64_decode(env('BASE64_APP_TITLE'))
@endphp
@extends('layouts.adminBase')
@section('title',$title)
@section('content')
    @component('layouts.content-header')
        @slot('title')@lang('admin.news')@lang('admin.manage')@endslot
        @slot('icon','book')
        @slot('nav')@endslot
        {{ $title }}
    @endcomponent
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header"></div>
                    <div class="box-body">
                        <form action="{{ route('admin.news.addNews') }}" class="form-horizontal" method="post">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label class="col-md-2 control-label">@lang('admin.title'):
                                    <span class="required">*</span>
                                </label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control form-filter" name="BASE64_APP_NAME" value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">@lang('admin.category'):
                                    <span class="required">*</span>
                                </label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control form-filter" name="BASE64_APP_NAME"
                                           value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">@lang('admin.description'):
                                    <span class="required">*</span>
                                </label>
                                <div class="col-md-10">
                                    <textarea class="form-control" name="" id="" cols="30" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">@lang('admin.thumb'):
                                    <span class="required">*</span>
                                </label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control form-filter" name="BASE64_APP_NAME" value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">@lang('admin.meta_keywords'):
                                    <span class="required">*</span>
                                </label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control form-filter" name="BASE64_APP_NAME" value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">@lang('admin.meta_desc'):
                                    <span class="required">*</span>
                                </label>
                                <div class="col-md-10">
                                    <textarea class="form-control" name="" id="" cols="30" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">@lang('admin.url'):
                                    <span class="required">*</span>
                                </label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control form-filter" name="BASE64_APP_NAME" value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">@lang('admin.islink'):
                                    <span class="required">*</span>
                                </label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control form-filter" name="BASE64_APP_NAME" value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">@lang('admin.sort'):
                                    <span class="required">*</span>
                                </label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control form-filter" name="BASE64_APP_NAME" value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">@lang('admin.content'):
                                    <span class="required">*</span>
                                </label>
                                <div class="col-md-10">
                                    <textarea class="" name="info[content]" id="content" cols="30"
                                              rows="10"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">@lang('admin.read'):
                                    <span class="required">*</span>
                                </label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control form-filter" name="BASE64_APP_NAME"
                                           value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">@lang('admin.like'):
                                    <span class="required">*</span>
                                </label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control form-filter" name="BASE64_APP_NAME" value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-10 col-md-offset-2">

                                    <div class="actions btn-set">
                                        <a type="button" name="back" onclick="window.history.go(-1)"
                                           class="btn btn-default"><i
                                                    class="fa fa-angle-left"></i> 返回</a>
                                        <button class="btn btn-default" type="reset"><i class="fa fa-refresh"></i> 重置
                                        </button>
                                        <button class="btn btn-success ajax-post no-refresh comfirm" type="submit">
                                            <i class="fa fa-check"></i> 保存
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
    <script>
        // 修复左侧菜单链接
        window._href = '{{ route('admin.news.addNews') }}';
        (function () {
            UE.delEditor('content');
//            UE.getEditor('_editor').render('_editor')
            var ue = UE.getEditor('content');
            ue.ready(function () {
                ue.execCommand('serverparam', '_token', '{{ csrf_token() }}'); // 设置 CSRF token.
            });
        })();
    </script>
@endsection
