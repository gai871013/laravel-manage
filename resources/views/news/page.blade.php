@extends('layouts.app')
@section('title', $title)
@section('keywords', $keywords)
@section('description', $description)
@section('content')
	<div class="content-wrap single">
		<div class="content">
			<header class="article-header">
				<h1 class="article-title">
					<a href="javascript:void(0);" draggable="false">{{ $news->title or '' }}</a>
				</h1>
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