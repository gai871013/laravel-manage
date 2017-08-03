@extends('layouts.a')
@section('section-content')
	<div class="box box-primary">
		<div class="box-header"></div>
		<div class="box-body">
			<form action="{{ route('admin.news.newsEdit') }}" class="form-horizontal" method="post">
				{{ csrf_field() }}
				<div class="form-group">
					<label class="col-md-2 control-label">@lang('admin.title'):
						<span class="required">*</span>
					</label>
					<div class="col-md-8">
						<input type="text" class="form-control form-filter" name="info[title]"
						       value="{{ $news->title or old('title') }}">
					</div>
					<div class="col-md-2">
						<input type="text" readonly class="form-control my-colorpicker1" name="info[style]"
						       value="{{ $category->style or old('style') }}">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label">@lang('admin.thumb'):
						<span class="required">*</span>
					</label>
					<div class="col-md-8">
						<div class="input-group">
							<input type="text" name="info[thumb]" value="{{ $news->thumb or old('thumb') }}"
							       class="filePath form-control filePath" readonly>
							<span class="input-group-btn">
                                <button onclick="daoru()" type="button"
                                        class="btn btn-info btn-flat"><i
			                                class="fa fa-picture-o"></i> @lang('admin.uploadPicture')</button>
                            </span>
							<input type="file" style="display: none;">
						</div>
					</div>
					<div class="col-md-2">
						@php
							$thumb = !empty($category->thumb) ? $category->thumb : '';
							$thumb = !empty(old('thumb')) ? old('thumb') : $thumb;
							$thumb = empty($thumb) ? asset('img/nopic.jpg') : asset('storage/'.$thumb);
						@endphp
						<img class="img-view" src="{{ $thumb }}" style="max-height: 100px; max-width:100%;">
					</div>
				</div>
				@if((isset($request['type']) && $request['type'] == 'single') || (isset($news->cat_id) && $news->cat_id == 0))
					<input type="hidden" name="info[cat_id]" value="0">
				@else
					<div class="form-group">
						<label class="col-md-2 control-label">@lang('admin.category'):
							<span class="required">*</span>
						</label>
						<div class="col-md-10">
							<select name="info[cat_id]" id="cat_id" class="form-control select2">
								@include('tree.categoriesSelect',['categories'=>$categories ,'id'=>$id ,'parent' => 0])
							</select>
						</div>
					</div>
				@endif
				<div class="form-group">
					<label class="col-md-2 control-label">@lang('admin.letter'):
						<span class="required">*</span>
					</label>
					<div class="col-md-10">
						<input type="text" class="form-control" name="info[pinyin]"
						       value="{{ $news->pinyin or old('pinyin') }}">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label">@lang('admin.description'):
						<span class="required">*</span>
					</label>
					<div class="col-md-10">
						<textarea class="form-control" name="info[description]" id="" cols="30"
						          rows="3">{{ $news->description or old('description') }}</textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label">@lang('admin.meta_keywords'):
						<span class="required">*</span>
					</label>
					<div class="col-md-10">
						<input type="text" class="form-control" name="info[meta_keywords]"
						       value="{{ $news->meta_keywords or old('meta_keywords') }}">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label">@lang('admin.content'):
						<span class="required">*</span>
					</label>
					<div class="col-md-10">
                                    <textarea class="" name="info[content]" id="content" cols="30"
                                              rows="10">{{ $news->content or old('content') }}</textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label">来源:
						<span class="required">*</span>
					</label>
					<div class="col-md-10">
						<input type="text" class="form-control" name="info[copyfrom]"
						       value="{{ $news->copyfrom or old('copyfrom') }}">
					</div>
				</div>
				<div class="form-group url" style="display: none;">
					<label class="col-md-2 control-label">@lang('admin.url'):
						<span class="required">*</span>
					</label>
					<div class="col-md-10">
						<input type="text" class="form-control" name="info[url]"
						       value="{{ $news->url or old('url') }}">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label">@lang('admin.islink'):
						<span class="required">*</span>
					</label>
					<div class="col-md-1">
						<select name="info[islink]" class="form-control is_link">
							<option value="0">@lang('admin.no')</option>
							<option value="1">@lang('admin.yes')</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label">是否允许评论:
						<span class="required">*</span>
					</label>
					<div class="col-md-1">
						<select name="info[allow_comment]" class="form-control">
							<option value="1">@lang('admin.yes')</option>
							<option value="0">@lang('admin.no')</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label">@lang('admin.sort'):
						<span class="required">*</span>
					</label>
					<div class="col-md-2">
						<input type="text" class="form-control form-filter" name="info[sort]"
						       value="{{ $news->sort or 100 }}">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label">@lang('admin.read'):
						<span class="required">*</span>
					</label>
					<div class="col-md-2">
						<input type="text" class="form-control" name="info[read]"
						       value="{{ $news->read or 0 }}">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label">@lang('admin.like'):
						<span class="required">*</span>
					</label>
					<div class="col-md-2">
						<input type="text" class="form-control" name="info[like]" value="{{ $news->like or 0 }}">
					</div>
				</div>
				@include('layouts.button', ['item' => $news])

			</form>
		</div>
	</div>
@endsection
@section('scripts')
	<script>
		@if((isset($request['type']) && $request['type'] == 'single') || (isset($news->cat_id) && $news->cat_id == 0))
        setUrl('{{ route('admin.news.singlePage') }}');
		@else
        setUrl('{{ route('admin.news.newsList') }}');
		@endif
        // 修复左侧菜单链接
        (function () {
            $('.my-colorpicker1').colorpicker();

            $('.box-body img').on('click', function () {
                $this = $(this);
                layer.open({
                    type: 1,
                    title: false,
                    closeBtn: 0,
                    area: ['640px', '640px'],
                    skin: 'layui-layer-rim', //没有背景色
                    shadeClose: true,
                    content: '<img style="max-width: 100%; margin: auto; display: block;" src="'
                    + $this.attr('src') + '" >'
                });
            });

            $('.is_link').on('change', function () {
                var val = $(this).val();
                if (val == 1) {
                    $('.url').show();
                } else {
                    $('.url').hide();
                }
            });

            UE.delEditor('content');
//            UE.getEditor('_editor').render('_editor')
            var ue = UE.getEditor('content');
            ue.ready(function () {
                ue.execCommand('serverparam', '_token', '{{ csrf_token() }}'); // 设置 CSRF token.
            });
        })();
	</script>
	@include('layouts.adminUpload')
@endsection
