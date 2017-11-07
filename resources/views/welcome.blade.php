@extends('layouts.app')
@section('title', isset($title) ? $title : config('app.name'))
@section('content')
	<div class="content-wrap">
		<div class="content">
			<div class="title">
				<h3>最新发布</h3>
			</div>
			@if(isset($news))
				@foreach($news as $v)
					@php
						$url = $v->cat_id == 0 ? route('page', ['id' => $v->id]) : route('show', ['id' => $v->id]);
						$thumb = !empty($v->thumb) ? Storage::url($v->thumb) : asset('img/nopic.jpg')
					@endphp
					<article class="excerpt excerpt-1">
						<a class="focus" href="{{ $url }}" title="{{ $v->title }}">
							<img class="thumb" data-original="{{ $thumb }}" src="{{ $thumb }}" alt="{{ $v->title }}">
						</a>
						<header>
							@if($v->cat_id > 0)
								<a class="cat" href="{{ route('category', ['id' => $v->cat_id]) }}"
								   draggable="false">{{ $v->category->catname or '' }}<i></i></a>
							@endif
							<h2><a href="{{ $url }}">{{ $v->title }}</a></h2>
						</header>
						<p class="meta">
							<time class="time"><i class="fa fa-clock-o"></i>{{ $v->created_at or '' }}</time>
							<span class="views"><i class="fa fa-eye"></i>共{{ $v->read or 0 }}人围观</span>
							<a class="comment" href="{{ $url }}#comment"></a>
						</p>
						<p class="note">{{ $v->description }}</p>
					</article>
				@endforeach
				{{ $news->links() }}
			@endif
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