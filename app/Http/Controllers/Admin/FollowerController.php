<?php

namespace App\Http\Controllers\Admin;

use App\models\FollowerTags;
use EasyWeChat\Foundation\Application;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class FollowerController extends Controller
{
    /**
     * 标签 2017-8-6 12:54:47 by gai871013
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getTags(Request $request)
    {
        $lists = FollowerTags::orderBy('id', 'asc')->get();
        return view('admin.user.tags', compact('lists'));
    }

    /**
     * 更新标签 2017-8-6 12:57:12 by gai871013
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getTagUpdate(Request $request)
    {
        $app = new Application(config('wechat'));
        $tag = $app->user_tag;
        try {
            $tags = $tag->lists()->toArray()['tags'];
            FollowerTags::truncate();
            FollowerTags::insert($tags);
        } catch (\Exception $exception) {
            Log::error($exception);
        }
        flash()->success(__('admin.operating') . __('admin.success'));
        return redirect()->back();
    }

    /**
     * 编辑、添加标签
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getTagEdit(Request $request)
    {
        $id = (int)$request->input('id');
        $remark = $request->input('remark');
        $app = new Application(config('wechat'));
        $tag = $app->user_tag;
        // 更新
        if ($id > 0) {
            try {
                $tag->update($id, $remark);
                FollowerTags::where('id', $id)->update(['name' => $remark]);
            } catch (\Exception $exception) {
                Log::error($exception);
            }
        } else {
            // 添加
            try {
                $tag->create($remark);
                FollowerTags::create(['name' => $remark]);
            } catch (\Exception $exception) {
                Log::error($exception);
            }
        }
        flash()->success(__('admin.operating') . __('admin.success'));
        return redirect()->back();
    }

    /**
     * 删除标签 2017-8-6 13:31:05 by gai871013
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getTagDelete(Request $request)
    {
        $id = (int)$request->input('id');
        $app = new Application(config('wechat'));
        $tag = $app->user_tag;
        FollowerTags::where('id', $id)->delete();

        try {
            $tag->delete($id);
        } catch (\Exception $exception) {
            Log::error($exception);
        }
        flash()->success(__('admin.operating') . __('admin.success'));
        return redirect()->back();
    }
}
