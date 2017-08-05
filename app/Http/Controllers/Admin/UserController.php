<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Follower;
use App\User;
use EasyWeChat\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * 前台用户管理 2017-4-19 16:42:15
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getUserManage(Request $request)
    {
        $title = trans('admin.userManage');
        $lists = User::orderBy('id', 'desc')->paginate(env('PAGE_SIZE'));
        return view('admin.user.userManage', compact('title', 'lists'));
    }

    /**
     * 编辑前台用户 2017-5-16 12:25:34 by gai871013
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getUserEdit(Request $request)
    {
        $id = (int)$request->get('id');
        $user = User::where('id', $id)->first();
        $title = trans('admin.edit');
        return view('admin.user.userEdit', compact('user', 'title', 'id'));
    }

    /**
     * 编辑前台用户Action 2017-5-16 14:32:33 by gai871013
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function postUserEdit(Request $request)
    {
        $data = $request->all()['info'];
        if ($data['id'] == 0) {
            $user = new User();
            $user->username = $data['username'];
            $user->email = $data['email'];
            $user->save();
            $data['id'] = $user->id;
        }
        User::where('id', $data['id'])->update($data);
        $next = route('admin.userManage');
        return view('info', compact('next'));
    }


    /**
     * 微信关注用户 2017-7-18 16:48:08 by gai871013
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getFollowerLists(Request $request)
    {
        $lists = Follower::orderBy('id', 'desc')->paginate(env('PAGE_SIZE'));
        return view('admin.user.followerLists', compact('lists'));
    }

    /**
     * 刷新微信用户
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
            $next = route('admin.follower.refresh', ['next' => $v]);
            $script = 'setUrl("' . $next . '")';
            $detail = '更新了' . $count . '条记录';
            return view('info', compact('script', 'next', 'detail'));
        }
        return redirect()->route('admin.follower');

    }

}
