<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Follower;
use App\models\FollowerGroups;
use App\models\FollowerTags;
use EasyWeChat\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FollowerController extends Controller
{


    /**
     * 微信关注用户 2017-7-18 16:48:08 by gai871013
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getFollowerLists(Request $request)
    {
        $type = $request->input('type');
        $black = empty($type) ? 0 : 1;
        $lists = Follower::orderBy('id', 'desc');
        if ($black > 0) {
            $lists = $lists->where('black', $black)->paginate(env('PAGE_SIZE'));
        } else {
            $lists = $lists->paginate(env('PAGE_SIZE'));
        }
        return view('admin.WeChat.follower.followerLists', compact('lists', 'type'));
    }

    /**
     * 刷新微信用户 2017-8-6 00:16:18 by gai871013
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getFollowerRefresh(Request $request)
    {
        set_time_limit(0);
        $next = $request->input('next');
        $app = new Application(config('wechat'));
        $userService = $app->user;
        $lists = $userService->lists($next);
        if (isset($lists->data['openid'])) {
            $data = $lists->data['openid'];
            $not_in_db = Follower::whereIn('openid', $data)->select('openid')->get()->toArray();
            $tmp = array_flatten($not_in_db);
            $insert = [];
            $v = '';
            $count = 0;
            foreach ($data as $v) {
                if (!in_array($v, $tmp)) {
                    $insert[] = [
                        'subscribe' => 1,
                        'subscribe_time' => 0,
                        'openid' => $v
                    ];
                    $count++;
                }
            }
            try {
                Follower::insert($insert);
            } catch (\Exception $exception) {
                Log::error($exception);
            }
            $next = route('admin.follower.refresh', ['next' => $lists->next_openid]);
            $script = 'setUrl("' . $next . '")';
            $detail = '更新了' . $count . '条记录';
            return view('info', compact('script', 'next', 'detail'));
        }
        return redirect()->route('admin.follower');
    }

    /**
     * 获取用户详情 2017-8-5 23:36:26 by gai871013
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getFollowerRefreshDetail(Request $request)
    {
        set_time_limit(0);
        $page = (int)$request->input('page');
        $openid = $request->input('openid');
        $app = new Application(config('wechat'));
        $userService = $app->user;
        if ($openid) {
            $user = $userService->get($openid)->toArray();
            $user['tagid_list'] = implode(',', $user['tagid_list']);
            Follower::where('openid', $user['openid'])->update($user);
            flash()->success(__('admin.operating') . __('admin.success'));
            return redirect()->route('admin.follower', ['page' => $page]);

        } else {
            $page = $page == 0 ? $page + 2 : $page + 1;
            $lists = Follower::select('openid')->orderBy('id', 'desc')->paginate(env('PAGE_SIZE'))->toArray();
            $data = array_flatten($lists['data']);
            if (count($data) > 0) {
                $users = $userService->batchGet($data)->toArray();
                foreach ($users['user_info_list'] as $user) {
                    $user['tagid_list'] = implode(',', $user['tagid_list']);
                    Follower::where('openid', $user['openid'])->update($user);
                }
                $title = '更新成功！';
                $detail = '更新了' . count($data) . '条记录 openID:[ "' . implode('" , "', $data) . '" "]';
                $next = route('admin.follower.refreshDetail', ['page' => $page]);
                $sec = 1;
                return view('info', compact('script', 'next', 'detail', 'sec', 'title'));
            }
            flash()->success(__('admin.operating') . __('admin.success'));
            return redirect()->route('admin.follower');
        }
    }

    /**
     * 粉丝备注 2017-8-6 00:20:38 by gai871013
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getFollowerRemark(Request $request)
    {
        // 更新微信
        $openid = $request->input('openid');
        $remark = $request->input('remark');
        $app = new Application(config('wechat'));
        $userService = $app->user;
        try {
            $res = $userService->remark($openid, $remark);
        } catch (\Exception $exception) {
            Log::error($exception);
        }
        Follower::where('openid', $openid)->update(['remark' => $remark]);
        flash()->success(__('admin.operating') . __('admin.success'));
        return redirect()->back();
    }

    /**
     * 黑名单操作 2017-8-6 01:19:47 by gai871013
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getFollowerBlack(Request $request)
    {
        $action = $request->input('action');
        $openid = $request->input('openid');
        $app = new Application(config('wechat'));
        $userService = $app->user;
        if ($action == 'black') {
            try {
                $userService->batchBlock([$openid]);
                Follower::where('openid', $openid)->update(['black' => 1]);
            } catch (\Exception $exception) {
                Log::error($exception);
            }
        }
        if ($action == 'unBlack') {
            try {
                $userService->batchUnblock([$openid]);
                Follower::where('openid', $openid)->update(['black' => 0]);
            } catch (\Exception $exception) {
                Log::error($exception);
            }
        }
        flash()->success(__('admin.operating') . __('admin.success'));
        return redirect()->back();
    }

    /**
     * 更新到黑名单列表 2017-8-6 01:36:28 by gai871013
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getFollowerBlackLists(Request $request)
    {
        set_time_limit(0);
        $next = $request->input('next');
        $app = new Application(config('wechat'));
        $userService = $app->user;
        try {
            $blacklist = $userService->blacklist($next)->toArray();
            $users = $blacklist['data']['openid'];
            if (count($users) > 1) {
                Follower::whereIn('openid', $users)->update(['black' => 1]);
                $next = route('admin.follower.blackLists', ['next' => $blacklist['next_openid']]);
                $detail = '本次更新' . count($users) . '条数据';
                $sec = 1;
                return view('info', compact('next', 'detail', 'sec'));
            } else {
                flash()->success(__('admin.operating') . __('admin.success'));
                return redirect()->route('admin.follower');
            }
        } catch (\Exception $exception) {
            Log::error($exception);
        }
        flash()->success(__('admin.operating') . __('admin.success'));
        return redirect()->route('admin.follower');
    }

    /**
     * 标签 2017-8-6 12:54:47 by gai871013
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getTags(Request $request)
    {
        $lists = FollowerTags::orderBy('id', 'asc')->get();
        return view('admin.WeChat.follower.tags', compact('lists'));
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

    /**
     * 粉丝分组管理 2017-8-7 11:21:42 by gai871013
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getGroups(Request $request)
    {
        $lists = FollowerGroups::orderBy('id', 'asc')->get();
        return view('admin.WeChat.follower.groups', compact('lists'));
    }

    /**
     * 更新分组 2017-8-8 14:51:02 by gai871013
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getGroupUpdate(Request $request, Application $app)
    {
//        $app = new Application(config('wechat'));
        $group = $app->user_group;
        try {
            $lists = $group->lists()->toArray()['groups'];

            FollowerGroups::truncate();
            FollowerGroups::insert($lists);
        } catch (\Exception $exception) {
            Log::error($exception);
        }
        flash()->success(__('admin.operating') . __('admin.success'));
        return redirect()->back();
    }

    /**
     * 编辑分组 2017-8-8 14:56:02 by gai871013
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getGroupEdit(Request $request)
    {
        $id = (int)$request->input('id');
        $remark = $request->input('remark');
        $app = new Application(config('wechat'));
        $group = $app->user_group;
        // 更新
        if ($id > 0) {
            try {
                $res = $group->update($id, $remark);
                FollowerGroups::where('id', $id)->update(['name' => $remark]);
            } catch (\Exception $exception) {
                Log::error($exception);
            }
        } else {
            // 添加
            try {
                $res = $group->create($remark);
                FollowerGroups::create($res->toArray()['group']);
            } catch (\Exception $exception) {
                Log::error($exception);
            }
        }
        flash()->success(__('admin.operating') . __('admin.success'));
        return redirect()->back();
    }

    /**
     * 删除分组 2017-8-8 15:20:42 by gai871013
     * @param Request $request
     * @param Application $app
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getGroupDelete(Request $request, Application $app)
    {
        $id = (int)$request->input('id');
        $group = $app->user_group;
        try {
            $group->delete($id);
            FollowerGroups::where('id', $id)->delete();
        } catch (\Exception $exception) {
            Log::error($exception);
        }
        flash()->success(__('admin.operating') . __('admin.success'));
        return redirect()->back();
    }
}
