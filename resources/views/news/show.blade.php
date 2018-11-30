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
			<!--PC和WAP自适应版-->
			<div id="SOHUCS" sid="{{ $news->id }}" ></div>
			<script type="text/javascript">
                (function(){
                    var appid = 'cyqUvIxsm';
                    var conf = 'prod_b426b30042abbc15e363cb679bbc937d';
                    var width = window.innerWidth || document.documentElement.clientWidth;
                    if (width < 960) {
                        window.document.write('<script id="changyan_mobile_js" charset="utf-8" type="text/javascript" src="https://changyan.sohu.com/upload/mobile/wap-js/changyan_mobile.js?client_id=' + appid + '&conf=' + conf + '"><\/script>'); } else { var loadJs=function(d,a){var c=document.getElementsByTagName("head")[0]||document.head||document.documentElement;var b=document.createElement("script");b.setAttribute("type","text/javascript");b.setAttribute("charset","UTF-8");b.setAttribute("src",d);if(typeof a==="function"){if(window.attachEvent){b.onreadystatechange=function(){var e=b.readyState;if(e==="loaded"||e==="complete"){b.onreadystatechange=null;a()}}}else{b.onload=a}}c.appendChild(b)};loadJs("https://changyan.sohu.com/upload/changyan.js",function(){window.changyan.api.config({appid:appid,conf:conf})}); } })(); </script>

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