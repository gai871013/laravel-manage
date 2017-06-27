<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * 分类管理 2017-4-25 10:18:33
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCategoryManage(Request $request)
    {
        $title = trans('admin.categoryManage');
        return view('admin.news.category', compact('title'));
    }

    /**
     * 新闻列表 2017-4-25 10:19:05
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getNewsList(Request $request)
    {
        $title = trans('admin.newsList');
        $lists = News::paginate(env('PAGE_SIZE'));
        return view('admin.news.newsList', compact('title', 'lists'));
    }

    /**
     * 编辑新闻
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getEditNews($id, Request $request)
    {
        $title = trans('admin.editNews');
        $news = News::where('id', $id)->first();
        return view('admin.news.addNews', compact('title', 'news'));
    }

    /**
     * 添加新闻
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAddNews(Request $request)
    {
        $title = trans('admin.addNews');
        return view('admin.news.addNews', compact('title'));
    }

    /**
     * 添加新闻单页
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAddSinglePage(Request $request)
    {
        $title = trans('admin.addSinglePage');
        return view('admin.news.addSinglePage', compact('title'));
    }

    public function getDeleteNews($id, Request $request)
    {
        $res = News::where('id', '=', $id)->delete();
        $title = trans('admin.delete');
        if ($res) {
            $title .= trans('admin.success');
        } else {
            $title .= trans('admin.fail');
        }
        $next = route('admin.news.newsList');
        $sec = 1;
        return view('info', compact('title', 'next', 'sec'));
    }
}
