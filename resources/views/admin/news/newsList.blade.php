@php
    $title = isset($title) ? $title : base64_decode(env('BASE64_APP_TITLE'))
@endphp
@extends('layouts.adminBase')
@section('title',$title)
@section('content')
    @component('layouts.content-header')
        @slot('title')@lang('admin.news')@lang('admin.manage')@endslot
        @slot('icon','list')
        @slot('nav')@endslot
        @lang('admin.newsList')
    @endcomponent
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header"></div>
                    <div class="box-body">
                        <table class="table table-bordered table-hover dataTable">
                            <tr>
                                <th>@lang('admin.id')</th>
                                <th>@lang('admin.title')</th>
                                <th>@lang('admin.category')</th>
                                <th>@lang('admin.description')</th>
                                <th>@lang('admin.sort')</th>
                                <th>@lang('admin.updated_at')</th>
                                <th>@lang('admin.operating')</th>
                            </tr>
                            @foreach($lists as $k => $v)
                                <tr>
                                    <td>{{ $v->id }}</td>
                                    <td>{{ $v->title }}</td>
                                    <td>{{ $v->cat_id }}</td>
                                    <td>{{ $v->description }}</td>
                                    <td>{{ $v->sort }}</td>
                                    <td>{{ $v->updated_at }}</td>
                                    <td>
                                        <a class="btn btn-success btn-xs" href="{{ route('admin.news.editNews',['id'=>$v->id]) }}"><i class="fa fa-edit"></i> @lang('admin.edit')</a>
                                        <a class="btn btn-danger btn-xs delete" href="{{ route('admin.news.deleteNews',['id'=>$v->id]) }}"><i class="fa fa-trash"></i> @lang('admin.delete')</a>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                        <div class="row">
                            {{ $lists->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')@endsection
