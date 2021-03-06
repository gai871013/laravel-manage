@extends('layouts.a')
@section('section-content')
	<div class="box box-primary">
		<div class="box-header"></div>
		<div class="box-body">
			<form action="{{ route('admin.news.category.edit') }}" class="form-horizontal" method="post">
				{{ csrf_field() }}

				<div class="form-group">
					<label class="col-md-2 control-label">@lang('admin.category'):
						<span class="required">*</span>
					</label>
					<div class="col-md-8">
						<input type="text" class="form-control form-filter" name="info[catname]"
						       value="{{ $category->catname or old('catname') }}">
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
							<input type="text" name="info[thumb]" value="{{ $category->thumb or old('thumb') }}"
							       class="filePath form-control" readonly>
							<span class="input-group-btn">
                                <button onclick="daoru()" type="button"
                                        class="btn btn-info btn-flat"><i
			                                class="fa fa-picture-o"></i> @lang('admin.uploadPicture')</button>
                            </span>
						</div>
						<input type="file" style="display: none;">
					</div>
					<div class="col-md-2">
						@php
							$thumb = !empty($category->thumb) ? $category->thumb : '';
							$thumb = !empty(old('thumb')) ? old('thumb') : $thumb;
							$thumb = empty($thumb) ? asset('img/nopic.jpg') : asset('storage/'.$thumb);
						@endphp
						<img class="img-view" src="{{ $thumb }}" style="max-height: 200px;">
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label">@lang('admin.parent')@lang('admin.category'):
						<span class="required">*</span>
					</label>
					<div class="col-md-10">
						<select name="info[parent_id]" id="parent_id" class="form-control">
							<option value="0">??????????????????</option>
							@php
								if(isset($category->parent_id)){
									$parent_id = $category->parent_id;
								}
							@endphp
							@include('tree.categoriesSelect', ['categories' => $categories, 'id' => $parent_id,'parent' => 0])
						</select>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label">@lang('admin.description'):
						<span class="required">*</span>
					</label>
					<div class="col-md-10">
                        <textarea name="info[description]" id="" cols="30" rows="3"
                                  class="form-control form-filter">{{ $category->description or old('description') }}</textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label">@lang('admin.url')</label>
					<div class="col-md-10">
						<input type="text" name="info[url]" class="form-control"
						       value="{{ $category->url or old('url') }}">
					</div>
				</div>
				<div class="form-group">
					<label for="" class="col-md-2 control-label">@lang('admin.letter')</label>
					<div class="col-md-10">
						<input type="text" class="form-control"
						       value="{{ $category->letter or old('letter') }}">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label">@lang('admin.sort')</label>
					<div class="col-md-2">
						<input type="text" name="info[sort]" class="form-control"
						       value="{{ $category->sort or 50 }}">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label">@lang('admin.is_menu')</label>
					<div class="col-md-2">
						<select name="info[is_menu]" class="form-control">
							<option @if(isset($category->is_menu) && $category->is_menu == 0) selected @endif value="0">
								???
							</option>
							<option @if(isset($category->is_menu) && $category->is_menu == 1) selected @endif value="1">
								???
							</option>
						</select>
					</div>
				</div>

				@include('layouts.button',['item' => $category])
			</form>
		</div>
	</div>
@endsection
@section('scripts')
	@include('layouts.adminUpload')
	<script>
		setUrl('{{ route('admin.news.categories') }}');
        $(function () {
            $('.my-colorpicker1').colorpicker();
        });
	</script>
@endsection