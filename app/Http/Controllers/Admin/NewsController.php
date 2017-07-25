<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * 分类管理 2017-4-25 10:18:33
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCategories(Request $request)
    {
        $categories = Categories::orderBy('sort', 'asc')->orderBy('id', 'desc')->get();
        return view('admin.news.category', compact('categories'));
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
     * 编辑栏目 2017-7-25 17:19:28 by gai871013
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCategoryEdit(Request $request)
    {
        $id = (int)$request->input('id');
        $category = Categories::where('id', $id)->first();
        $categories = Categories::where('id', '!=', $id)->orderBy('sort', 'asc')->orderBy('id', 'desc')->get();
        return view('admin.news.categoryEdit', compact('category', 'id', 'categories'));
    }

    public function postCategoryEdit(Request $request)
    {
        $data = $request->input('info');
        return $data;
    }

    /**
     * 编辑新闻
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getNewsEdit(Request $request)
    {
        $id = (int)$request->input('id');
        $news = News::where('id', $id)->first();
        $categories = Categories::orderBy('sort', 'asc')->get();
        return view('admin.news.newsEdit', compact('news', 'categories', 'id'));
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

    /**
     * 删除文章 2017-7-25 17:07:34 by gai871013
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getNewsDelete(Request $request)
    {
        $id = (int)$request->input('id');
        $res = News::where('id', '=', $id)->delete();
        $title = trans('admin.delete');
        if ($res) {
            $title .= trans('admin.success');
        } else {
            $title .= trans('admin.fail');
        }
        flash($title)->success();
        return redirect()->back();
    }
}
