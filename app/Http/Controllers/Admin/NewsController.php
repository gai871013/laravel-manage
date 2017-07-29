<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use App\Models\Comments;
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
        $lists = News::where('status', 1)->where('cat_id', '>', 0)->paginate(env('PAGE_SIZE'));
        return view('admin.news.newsList', compact('lists'));
    }

    /**
     * 编辑栏目 2017-7-25 17:19:28 by gai871013
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCategoryEdit(Request $request)
    {
        $id = (int)$request->input('id');
        $parent_id = (int)$request->input('parent_id');
        $category = Categories::where('id', $id)->first();
        $categories = Categories::where('id', '!=', $id)->orderBy('sort', 'asc')->orderBy('id', 'desc')->get();
        return view('admin.news.categoryEdit', compact('category', 'id', 'categories', 'parent_id'));
    }

    /**
     * 保存栏目 2017-7-26 10:49:32 by gai871013
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCategoryEdit(Request $request)
    {
        $data = $request->input('info');
        $id = (int)$data['id'];
        $parent_id = (int)$data['parent_id'];
        unset($data['id']);
        // todo 判断分类下是否有文章
        // 编辑分类
        if ($id > 0) {
            $old = Categories::where('id', $id)->first();
            $son = isset($old->child_id) && !empty($old->child_id) ? explode(',', $old->child_id) : [];
            if ($id == $parent_id || in_array($parent_id, $son)) {
                flash()->error(__('admin.edit') . __('admin.fail') . '!父ID不能是自己或者是自己子分类');
                return redirect()->back()->withInput($data);
            }
            $data['child_id'] = implode(',', array_merge($son, [$id]));
            Categories::where('id', $id)->update($data);
            // 更新就栏目父级栏目关系
            $this->updateCategoryParent($old->parent_id);
        } else { // 添加分类
            $parent = $this->categoryParent($parent_id);
            $parent = array_unique(array_merge($parent, [$parent_id]));
            sort($parent);
            $data['arr_parent_id'] = implode(',', $parent);
            // 添加记录到数据库
            $category = Categories::create($data);
            // 更新子分类
            Categories::where('id', $category->id)->update(['child_id' => $category->id]);
        }
        // 更新父级分类
        $this->updateCategoryParent($parent_id);
        // 通知成功
        flash()->success(__('admin.save') . __('admin.success'));
        return redirect()->route('admin.news.categories');
    }


    /**
     * 获取父级分类 2017-7-26 11:05:15 by gai871013
     * @param $cat_id
     * @return array
     */
    public function categoryParent($cat_id)
    {
        $category = Categories::where('id', $cat_id)->first();
        $arr_parent_id = [];
        if (!empty($category)) {
            $arr_parent_id[] = $parent_id = $category->parent_id;
            if ($parent_id > 0) {
                $parent = $this->categoryParent($parent_id);
                $arr_parent_id = array_merge($arr_parent_id, $parent);
            }
        }
        sort($arr_parent_id);
        return $arr_parent_id;
    }

    /**
     * 更新栏目
     * @param $cat_id
     */
    public function updateCategoryParent($cat_id)
    {
        $category = Categories::where('id', $cat_id)->lockForUpdate()->first();
        if (!empty($category)) {
            $son = $this->categorySon($cat_id);
            $parent = $this->categoryParent($cat_id);
            $sonStr = implode(',', $son);
            $parentStr = implode(',', $parent);
            $category->child_id = $sonStr;
            $category->arr_parent_id = $parentStr;
            if (count($son) == 1) {
                $category->child = 0;
            } else {
                $category->child = 1;
            }
            $category->save();
            $this->updateCategoryParent($category->parent_id);
//            $category->child
        }
    }

    /**
     * 获取该分类下所有分类
     * @param $cat_id
     * @return array
     */
    public function categorySon($cat_id)
    {
        $child_id = [$cat_id];
        $category = Categories::where('parent_id', $cat_id)->get();
        if (!empty($category)) {
            foreach ($category as $v) {
                $child_id[] = $v->id;
                $children = $this->categorySon($v->id);
                $child_id = array_merge($child_id, $children);
            }
        }
        $child_id = array_unique($child_id);
//        Categories::where('id', $cat_id)->update(['child_id' => implode(',', $child_id)]);
        return $child_id;
    }

    /**
     * 排序
     * @param Request $request
     * @return array|string
     */
    public function postCategoriesSort(Request $request)
    {
        $data = $request->input('info');
        foreach ($data['id'] as $k => $v) {
            Categories::where('id', $v)->update(['sort' => $data['sort'][$k]]);
        }
        flash()->success(__('admin.save') . __('admin.success'));
        return redirect()->back();
    }

    /**
     * 删除分类
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getCategoryDelete(Request $request)
    {
        $id = $request->input('id');
        $category = Categories::find($id);
        if (!empty($category) && $category->child_id != $id) {
            flash(__('admin.delete') . __('admin.fail') . ',请确保分类为终极分类')->error();
        } else {
            $news = News::where('cat_id', $id)->first();
            if (!empty($news)) {
                flash(__('admin.delete') . __('admin.fail') . ',请先转移该分类下的内容')->error();
            } else {
                Categories::where('id', $id)->delete();
                $this->updateCategoryParent($category->parent_id);
                flash(__('admin.delete') . __('admin.success'))->success();
            }
        }
//        flash(__('admin.delete') . __('admin.success'))->success();
        return redirect()->back();
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
        return view('admin.news.newsEdit', compact('news', 'categories', 'id', 'request'));
    }


    public function postNewsEdit(Request $request)
    {
        $data = $request->input('info');

        // 编辑文章
        if ($data['id'] && $data['id'] > 0) {
            News::where('id', $data['id'])->update($data);
        } else {
            unset($data['id']);
            News::create($data);
        }
        if ($data['cat_id'] > 0) {
            $route = 'admin.news.newsList';
        } else {
            $route = 'admin.news.singlePage';
        }
        flash(__('admin.save') . __('admin.success'))->success();
        return redirect()->route($route);
    }

    /**
     * 添加新闻单页
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getSinglePage(Request $request)
    {
        $lists = News::where('cat_id', 0)->paginate(env('PAGE_SIZE'));
        return view('admin.news.singlePage', compact('lists'));
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

    /**
     * 评论管理 2017-7-27 11:46:07 by gai871013
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getComment(Request $request)
    {
        $lists = Comments::orderBy('id', 'desc')->paginate(env('PAGE_SIZE'));
        return view('admin.news.comment', compact('lists'));
    }
}
