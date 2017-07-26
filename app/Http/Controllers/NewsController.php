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
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function getCategory($id, Request $request)
    {
        $category = Categories::find($id);
        if (empty($category)) {
            return redirect()->route('index');
        }
        $son = explode(',', $category->child_id);
        $news = News::whereIn('cat_id', $son)->paginate(env('PAGE_SIZE'));
        return view('news.category', compact('category', 'news'));
    }

}