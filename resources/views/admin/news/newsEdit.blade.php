@extends('layouts.a')
@section('section-content')
    <div class="box box-primary">
        <div class="box-header"></div>
        <div class="box-body">
            <form action="{{ route('admin.news.newEdit') }}" class="form-horizontal" method="post">
                {{ csrf_field() }}
                <div class="form-group">
                    <label class="col-md-2 control-label">@lang('admin.title'):
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-10">
                        <input type="text" class="form-control form-filter" name="info[title]"
                               value="{{ $news->title or old('title') }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">@lang('admin.category'):
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-10">
                        <select name="info[cat_id]" id="cat_id" class="form-control select2">
                            @include('tree.categoriesSelect',['categories'=>$categories ,'id'=>$id])
                        </select>
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
                        <input type="text" class="form-control" name="BASE64_APP_NAME"
                               value="">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">@lang('admin.like'):
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" name="BASE64_APP_NAME" value="">
                    </div>
                </div>
                @include('layouts.button', ['item' => $news])

            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        // 修复左侧菜单链接
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
