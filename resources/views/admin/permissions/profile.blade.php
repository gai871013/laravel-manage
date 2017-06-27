@php
    $title = isset($title) ? $title : base64_decode(env('BASE64_APP_TITLE'))
@endphp
@extends('layouts.adminBase')
@section('title',$title)
@section('content')
    @component('layouts.content-header')
        @slot('title')@lang('admin.permissionsManage')@endslot
        @slot('icon','users')
        @slot('nav')@endslot
        @lang('admin.profile')
    @endcomponent
    <section class="content">
        <div class="box box-primary">
            <div class="box-header"></div>
            <div class="box-body">
                <form action="{{ route('admin.user.profile') }}" class="form-horizontal" method="post">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="col-md-2 control-label">@lang('admin.username'):
                            <span class="required">*</span>
                        </label>
                        <div class="col-md-10">
                            <input type="text" class="form-control form-filter" name="info[username]"
                                   value="{{ $user->username or '' }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">@lang('admin.email'):
                            <span class="required">*</span>
                        </label>
                        <div class="col-md-10">
                            <input type="text" class="form-control form-filter" name="info[email]"
                                   value="{{ $user->email or '' }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">@lang('admin.password'):
                            <span class="required">*</span>
                        </label>
                        <div class="col-md-10">
                            <input type="text" class="form-control form-filter" name="info[password]" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">@lang('admin.passwordConfirmation'):
                            <span class="required">*</span>
                        </label>
                        <div class="col-md-10">
                            <input type="text" class="form-control form-filter" name="info[password_confirmation]"
                                   value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">@lang('admin.name'):
                            <span class="required">*</span>
                        </label>
                        <div class="col-md-10">
                            <input type="text" class="form-control form-filter" name="info[name]"
                                   value="{{ $user->name or '' }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">@lang('admin.nickname'):
                            <span class="required">*</span>
                        </label>
                        <div class="col-md-10">
                            <input type="text" class="form-control form-filter" name="info[nickname]"
                                   value="{{ $user->nickname or '' }}">
                        </div>
                    </div>
                    @if($next)
                        <div class="form-group">
                            <label for="role_id" class="col-md-2 control-label">@lang('admin.role')</label>
                            <div class="col-md-10">
                                <select name="info[role_id]" id="role_id" class="form-control">
                                    @foreach($roles as $v)
                                        <option value="{{ $v->id }}"
                                                @if(isset($user->role_id) && $user->role_id == $v->id) selected @endif >{{ $v->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif
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
                                <input type="hidden" name="next" value="{{ $next or route('admin.user.profile') }}">
                                <input type="hidden" name="info[id]" value="{{ $user->id or 0 }}">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
@section('scripts')@endsection
