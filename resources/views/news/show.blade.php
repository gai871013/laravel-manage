@extends('layouts.app')
@section('title', $news->title)
@section('keywords', $news->title)
@section('description', $news->description)
@section('content')
	<div class="content-wrap single">
		<div class="content">
			<header class="article-header">
				<h1 class="article-title">
					<a href="javascript:void(0);" draggable="false">{{ $news->title or '' }}</a>
				</h1>
				<div class="article-meta">
                <span class="item article-meta-time">
	                <time class="time" data-toggle="tooltip" data-placement="bottom" title=""
	                      data-original-title="时间：{{ $news->created_at or '' }}">
                        <i class="fa fa-clock-o">{{ $news->created_at or '' }}</i>
                    </time>
	            </span>
					<span class="item article-meta-source" data-toggle="tooltip" data-placement="bottom" title=""
					      data-original-title="来源：{{ config('app.name') }}">
                    <i class="fa fa-globe"></i> {{ config('app.name') }}
                </span>
					<span class="item article-meta-category" data-toggle="tooltip" data-placement="bottom" title=""
					      data-original-title="栏目：{{ $news->category->catname or '' }}">
                    <i class="fa fa-list"></i>
                    <a href="{{ route('category', ['id' => $news->cat_id]) }}" title=""
                       draggable="false">{{ $news->category->catname or '' }}</a>
                </span>
					<span class="item article-meta-views" data-toggle="tooltip" data-placement="bottom" title=""
					      data-original-title="查看：{{ $news->read or 0 }}">
                    <i class="fa fa-eye"></i> 阅读 ({{ $news->read or 0 }})
                </span>
				</div>
			</header>
			<article class="article-content">
				{!! $news->content or '' !!}
			</article>
		</div>
	</div>
	<aside class="sidebar">
		<div class="fixed">
			<div class="widget widget_search">
				<form class="navbar-form" action="" method="post">
					<div class="input-group">
						<input type="text" id="keyword" class="form-control" size="35" placeholder="请输入关键字"
						       maxlength="15" autocomplete="off">
						<span class="input-group-btn">
            <button class="btn btn-default btn-search" id="search" type="button">搜索</button>
            </span></div>
				</form>
			</div>
		</div>
		<div class="widget widget_hot">
			<h3>热门文章</h3>
			<ul>
				{{--<li><a href="http://www.ice-breaker.cn/post/1" draggable="false"><span class="thumbnail"><img
									class="thumb" data-original="images/excerpt.jpg"
									src="http://www.ice-breaker.cn/public/uploads/thumbnail/17041203582751.jpg" alt=""
									draggable="false"></span><span class="text">php的20个小细节</span><span class="muted"><i
									class="glyphicon glyphicon-time"></i>2017-07-29 11:29:33</span><span
								class="muted"><i class="glyphicon glyphicon-eye-open"></i>499</span></a></li>--}}
			</ul>
		</div>
	</aside>
@endsection