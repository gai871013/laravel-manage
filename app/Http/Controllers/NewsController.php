<?php
/**
 * Created by PhpStorm.
 * User: wanggaichao @gai871013
 * Date: 2017-07-26
 * Time: UTC/GMT+08:00 17:55
 * laravel-manage/NewsController.php
 */

namespace App\Http\Controllers;


use App\Models\Categories;
use App\Models\News;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function __construct()
    {
    }

    /**
     * 栏目页面 2017-7-27 12:04:38 by gai871013
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getCategory($id, Request $request)
    {
        $url = 'http://dwz.cn/create.php';
        $params = [
            'url' => 'http://ebzzlt.bzh001.com/WeChat/SmsMarketing?invite=9999910',
            'alias' => '',
            'access_type' => 'web'
        ];
        $client = new Client();
        $response = $client->post($url, ['form_params' => $params])->getBody()->getContents();
        return $response;
        $category = Categories::find($id);
        if (empty($category)) {
            return redirect()->route('index');
        }
        $son = explode(',', $category->child_id);
        $news = News::whereIn('cat_id', $son)->paginate(env('PAGE_SIZE'));
        $title = $category->catname;
        return view('news.category', compact('category', 'news', 'title'));
    }

    /**
     * 获取内容 2017-7-27 12:04:32 by gai871013
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getShow($id, Request $request)
    {
        $news = News::find($id);
        return view('news.show', compact('news'));
    }

    /**
     * 获取单页 2017-7-27 12:04:27 by gai871013
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getPage($id, Request $request)
    {
        $news = News::find($id);
        $title = $news->title;
        $keywords = $news->title;
        $description = $news->description;
        return view('news.page', compact('news', 'title', 'keywords', 'description'));
    }

}